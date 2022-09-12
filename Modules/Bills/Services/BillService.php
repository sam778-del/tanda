<?php


namespace Modules\Bills\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;
use Modules\Bills\Models\Bill;
use Modules\Bills\Models\BillTransaction;
use Modules\Bills\Processors\PrimeAirtime\PrimeAirtime;
use Modules\Bills\Processors\Vtpass\Vtpass;

class BillService
{
    use ApiResponse;

    public function getServiceById($id)
    {
        return Bill::where("id", $id)->first();
    }

    public function getServices(string $service)
    {
        $bill = Bill::type()->firstWhere('slug', $service);
        return Bill::where('vendor', $bill['vendor'])
            ->where('category', $bill['category'])
            ->where("status", true)
            ->get();
    }

    /**
     * @throws \Exception
     */
    public function verify(Bill $bill, string $msisdn)
    {
        switch ($bill->category) {
            case Bill::MOBILE_DATA:
            case Bill::AIRTIME:
                return $this->verifyAirtimeAndMobileData($msisdn, $bill);
            case Bill::ELECTRICITY:
            case Bill::TV_SUBSCRIPTION:
                return $this->verifyElectricityAndCableTv($msisdn, $bill);
            default:
                throw new \Exception("Invalid service type");
        }
    }

    public function createTransaction(array $payload)
    {
        return DB::transaction(function () use ($payload) {
            $this->ensureUserCanPurchaseBill(auth()->user(), $payload['amount']);

            $billTransaction = BillTransaction::createBillTransaction(
                Arr::get($payload, 'bill.id'),
                $payload['user_id'],
                $payload['amount'],
                $payload['transaction_ref'],
                BillTransaction::PENDING,
                $payload['type'],
            );
            $transaction = $billTransaction->transaction()->create([
                'user_id' => $payload['user_id'],
                'amount' => $payload['amount'],
                'status' => Transaction::PENDING,
                'transaction_ref' => Arr::get($payload, 'transaction_ref')
            ]);

            return $billTransaction->setRelation('transaction', $transaction)->setRelation('bill', $payload['bill']);
        });
    }

    /**
     * @param Bill $bill
     * @param string $msisdn
     * @param float $amount
     * @param string $transactionRef
     * @param BillTransaction $billTransaction
     * @return false|mixed
     */
    public function purchase(Bill $bill, string $msisdn, float $amount, string $transactionRef, BillTransaction $billTransaction)
    {
        $payload = [
            Bill::MOBILE_DATA => 'purchaseWithPrimeAirtime',
            Bill::AIRTIME => 'purchaseWithPrimeAirtime',
            Bill::ELECTRICITY => 'purchaseWithVtpass',
            Bill::TV_SUBSCRIPTION => 'purchaseWithVtpass',
        ];

        return call_user_func(
            [self::class, $payload[$bill->category]],
            $bill,
            $msisdn,
            $amount,
            $transactionRef,
            $billTransaction
        );
    }

    public function query(BillTransaction $billTransaction)
    {
        $payload = [
            Bill::MOBILE_DATA => 'reQueryPrimeAirtimeTransaction',
            Bill::AIRTIME => 'reQueryPrimeAirtimeTransaction',
            Bill::ELECTRICITY => 'reQueryWithVtpassTransaction',
            Bill::TV_SUBSCRIPTION => 'reQueryWithVtpassTransaction',
        ];

        return call_user_func(
            [self::class, $payload[$billTransaction->bill->category]],
            $billTransaction
        );
    }

    private function ensureUserCanPurchaseBill(User $user, float $amount): bool
    {
        if ((float)$user->wallet->actual_amount < $amount) {
            abort(400, "Insufficient funds");
        }
        return true;
    }

    private function verifyAirtimeAndMobileData(string $number, Bill $bill)
    {
        $billObject = $this->verifyChannels($bill);
        $response = app($billObject)->verifyPhoneNumber($bill, $number);

        if (!Arr::get($response, 'opts.canOverride')) {
            return new Fluent([
                "status" => false,
                "message" => "The phone number is not valid"
            ]);
        }

        if (!is_null(Arr::get($response, 'opts.operator'))) {
            return new Fluent([
                "status" => true,
                "message" => "The phone number is valid",
                "data" => ['network' => $bill->group_name, 'biller_number' => $number]
            ]);
        }

        return new Fluent([
            "status" => false,
            "message" => "The phone number is does not match {$bill->group_name} operator"
        ]);
    }

    private function verifyElectricityAndCableTv(string $number, Bill $bill)
    {
        $billObject = $this->verifyChannels($bill);
        $response = app($billObject)->verifyElectricityAndCableTv($bill, $number);

        if (Arr::get($response, 'content.Customer_Name') != null) {
            $payload = [
                'name' => Arr::get($response, 'content.Customer_Name'),
                'biller_number' => $number,
            ];
            if ($bill->category == Bill::TV_SUBSCRIPTION) {
                $payload['due_date'] = Arr::get($response, 'content.Due_Date');
            }
            if ($bill->category == Bill::ELECTRICITY) {
                $payload['address'] = Arr::get($response, 'content.Address');
            }

            return new Fluent([
                "status" => true,
                "message" => "This number is valid",
                "data" => $payload
            ]);
        }

        return new Fluent([
            "status" => false,
            "message" => "This number is not valid"
        ]);
    }

    /**
     * @param Bill $bill
     * @param string $msisdn
     * @param float $amount
     * @param string $transactionRef
     * @param BillTransaction $billTransaction
     * @return array|Fluent|object
     */
    private function purchaseWithPrimeAirtime(Bill $bill, string $msisdn, float $amount, string $transactionRef, BillTransaction $billTransaction)
    {
        $billObject = $this->verifyChannels($bill);
        $response = resolve($billObject)->purchase($bill, $msisdn, $amount, $transactionRef);

        if (Arr::get($response, 'status') == 201) {
            $payload = [
                'amount' => Arr::get($response, 'topup_amount'),
                'number' => Arr::get($response, 'target'),
                'transaction_ref' => Arr::get($response, 'customer_reference')
            ];
            if (Arr::get($response, 'pin_based')) {
                $payload['pin'] = Arr::get($response, 'pin_code');
            }

            $billTransaction->status = BillTransaction::SUCCESS;
            $billTransaction->save();

            $billTransaction->transaction->status = Transaction::SUCCESS;
            $billTransaction->transaction->save();

            return $this->successResponse('Transaction successful', $payload);
        } elseif (Arr::get($response, 'status') == 208) {
            return $this->successResponse('Bill request is being processed', [
                'transaction_ref' => Arr::get($response, 'customer_reference'),
            ]);
        }

        abort(400, Arr::get($response, 'message'));
    }

    private function reQueryPrimeAirtimeTransaction(BillTransaction $billTransaction)
    {
        $billObject = $this->verifyChannels($billTransaction->bill);
        $response = resolve($billObject)->reQuery($billTransaction->transaction_ref);
    }

    private function reQueryWithVtpassTransaction(BillTransaction $billTransaction)
    {
        $billObject = $this->verifyChannels($billTransaction->bill);
        $response = resolve($billObject)->reQuery($billTransaction->transaction_ref);
    }

    /**
     * @param Bill $bill
     * @param string $msisdn
     * @param float $amount
     * @param string $transactionRef
     * @param BillTransaction $billTransaction
     * @return array|Fluent|object
     */
    private function purchaseWithVtpass(Bill $bill, string $msisdn, float $amount, string $transactionRef, BillTransaction $billTransaction)
    {
        $billObject = $this->verifyChannels($bill);
        $response = resolve($billObject)->purchase($bill, $msisdn, $amount, $transactionRef);

        if (Arr::get($response, 'content.transactions.status') == 'delivered') {
            if ($bill->service_code == 'prepaid') {
//                return $this->ok([
//                    'token' => Arr::get($response, 'mainToken'),
//                    'transaction_ref' => Arr::get($response, 'requestId')
//                ]);
            } else {
//                return $this->ok([
//                    'transaction_ref' => Arr::get($response, 'requestId')
//                ]);
            }

            $billTransaction->status = BillTransaction::SUCCESS;
            $billTransaction->save();

            $billTransaction->transaction->status = Transaction::SUCCESS;
            $billTransaction->transaction->save();
        }
        return $this->bad(Arr::get($response, 'response_description'));
    }

    private function handlePrivateResponse(BillTransaction $billTransaction)
    {
        $billTransaction->status = BillTransaction::SUCCESS;
        $billTransaction->save();

        $billTransaction->transaction->status = Transaction::SUCCESS;
        $billTransaction->transaction->save();
    }

    private function verifyChannels(Bill $bill)
    {
        $bills = [
            Bill::AIRTIME => PrimeAirtime::class,
            Bill::MOBILE_DATA => PrimeAirtime::class,
            Bill::TV_SUBSCRIPTION => Vtpass::class,
            Bill::ELECTRICITY => Vtpass::class,
        ];
        return Arr::get($bills, $bill->category);
    }
}
