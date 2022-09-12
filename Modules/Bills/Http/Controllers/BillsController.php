<?php

namespace Modules\Bills\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Bills\Http\Requests\VerifyBillRequest;
use Modules\Bills\Models\Bill;
use Modules\Bills\Models\BillTransaction;
use Modules\Bills\Services\BillService;

class BillsController extends Controller
{
    private BillService $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    /**
     * @param string $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(string $service)
    {
        $billCategory = Bill::type()->firstWhere("slug", strtolower($service));
        if (empty($billCategory)) {
            return $this->badRequestAlert("Service not supported");
        }

        $refills = Bill::where('vendor', $billCategory['vendor'])
            ->where("category", $billCategory['category'])
            ->where("status", true)
            ->orderBy("amount")
            ->get();

        return $this->successResponse("Bills retrieved successfully", $refills);
    }

    public function verifyService(VerifyBillRequest $request)
    {
        $response = $this->billService->verify($request->bill, $request->msisdn);
        if ($response->status) {
            return $this->successResponse($response->message, $response->data);
        }
        return $this->badRequestAlert($response->message);
    }

    /**
     * @param VerifyBillRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchase(VerifyBillRequest $request): \Illuminate\Http\JsonResponse
    {
        $purchase = DB::transaction(function () use ($request) {
            //Lock user wallet
            auth()->user()->lockWallet();

            //Create a transaction and debit user first
            $type = $this->getBillTransactionType($request->bill->category);
            $transactionRef = Transaction::generateRef();

            $createTransaction = [
                'bill' => $request->bill,
                'amount' => (float)$request->amount,
                'user_id' => auth()->user()->id,
                'transaction_ref' => $transactionRef,
                'type' => $type
            ];

            $billTransaction = $this->billService->createTransaction($createTransaction);

            //Purchase bill order
            return $this->billService->purchase(
                $request->bill,
                $request->msisdn,
                $request->amount,
                $transactionRef,
                $billTransaction
            );
        });

        if ($purchase->status) {
            return $this->createdResponse('Your bill transaction was successful', $purchase->data);
        }
        return $this->badRequestAlert($purchase->message);
    }

    private function getBillTransactionType(string $category)
    {
        $bills = [
            Bill::AIRTIME => BillTransaction::TRANSACTION_TYPE['airtime'],
            Bill::MOBILE_DATA => BillTransaction::TRANSACTION_TYPE['data'],
            Bill::TV_SUBSCRIPTION => BillTransaction::TRANSACTION_TYPE['cable'],
            Bill::ELECTRICITY => BillTransaction::TRANSACTION_TYPE['electricity'],
        ];
        return Arr::get($bills, $category);
    }
}
