<?php


namespace Modules\Bills\Traits;


use App\Exceptions\ExceptionWithErrorData;
use Exception;
use Modules\Bills\Models\BillTransaction;
use Modules\Bills\Repositories\BillRepository ;
use Modules\Bills\Repositories\BillTransactionRepository;
use Propaganistas\LaravelPhone\PhoneNumber;

trait BillsTrait
{

    private BillTransactionRepository $serviceTransactionRepository;

    public function __construct(BillTransactionRepository $serviceTransactionRepository)
    {
        $this->serviceTransactionRepository = $serviceTransactionRepository;
    }

    public function generateRef(): string
    {
        return time() . rand(10 * 45, 100 * 98);

    }

    public function logServiceTransaction(array $data)
    {
        return $this->serviceTransactionRepository->storeServiceTransaction($data);

    }

    public function updateServiceTransactionLog(BillTransaction $serviceTransaction, $data)
    {
        return $serviceTransaction->serviceTransactionLog()->update($data);
    }

    public function getProcessor()
    {
        return config(module_path("Bills", "config.bills_processor"), "airvend");
    }

    /**
     * @throws Exception
     */
    private function normalizePhoneNumber(string $phone_number): string
    {
        try {
            return str_replace(' ', '', PhoneNumber::make($phone_number, 'NG')->formatNational());
        } catch (Exception $e) {
            throw new Exception('Invalid phone number provided.');
        }
    }



}
