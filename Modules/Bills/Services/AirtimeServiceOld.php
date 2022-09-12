<?php

namespace Modules\Bills\Services;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Bills\Interfaces\IService;
use Modules\Bills\Models\BillTransaction;
use Modules\Bills\Processors\ProcessorResolver;
use Modules\Bills\Repositories\BillRepository;
use Modules\Bills\Repositories\BillTransactionRepository;
use Modules\Bills\Traits\BillsTrait;

class AirtimeServiceOld implements IService
{
    use BillsTrait, ApiResponse;
    public $serviceRepo;

    public $serviceRequest = "airtime";

    public function __construct(BillRepository $serviceRepository)
    {
        $this->serviceRepo = $serviceRepository;
    }

    public function requestValue(array $requestData)
    {
        $this->requestData = $requestData;
        try {
            $endpoint = config("airvend.services.purchase");
            $service = $this->serviceRepo->getServiceById($requestData["service_id"]);
            $ref = $this->generateRef();

            $serviceTransactionData = [
                "user_id" => getUser()->id,
                "service_id" => $service->id,
                "amount" => $requestData["amount"],
                "processor" => $this->getProcessor(),
                "transaction_ref" => $ref,
                "narration" => "Airtime purchase on {$requestData["phone_no"]}"
            ];

            $serviceTransaction = $this->logServiceTransaction($serviceTransactionData);
            if (! $serviceTransaction) {
                return $this->badRequestAlert("Unable to log service transaction");
            }

            $processor = (new ProcessorResolver(new BillTransactionRepository()))->initiate();
            $requestPayload = $processor->getPurchaseRequestData($requestData, $this, $serviceTransaction);

            if (empty($requestPayload)) {
                return $this->badRequestAlert("Unable to log service transaction");
            }

            $this->updateServiceTransactionLog($serviceTransaction, [
                "processor_request_payload" => json_encode($requestPayload)
            ]);

            $result = $processor->makePurchase($endpoint, $requestPayload);
            if ($result['status']) {
                //update log
                $this->updateServiceTransactionLog($serviceTransaction, [
                    "processor_ref" => (isset($result["data"]["details"]["TransactionID"])) ? $result["data"]["details"]["TransactionID"] : null,
                    "processor_response_payload" => json_encode($result["data"])
                ]);
            }


            switch (Str::lower($result["status"])) {
                case "success":
                    $this->updateServiceTransaction($serviceTransaction, [
                        "status" => BillTransaction::SUCCESS,
                    ]);
                    $serviceTransaction->refresh();
                    $response = [
                        'transaction_ref' => $serviceTransaction->transaction_ref,
                        'transaction_status' => $serviceTransaction->status,
                        'service' => $serviceTransaction->service,
                        'account' => (isset($result["data"]["details"]["account"])) ? $result["data"]["details"]["account"] : $input["phone_no"],
                        'transaction_message' => $serviceTransaction->narration,
                    ];
                    $this->updateServiceTransaction($serviceTransaction, [
                        "user_response_payload" => json_encode($response),
                    ]);

                    return $this->successResponse("Successful", $response);
                    break;
                case "error":
                    logger($result);
                    $this->updateServiceTransaction($serviceTransaction, [
                        "status" => BillTransaction::FAILED,
                    ]);
                    $error = $result["message"] ?? "Transaction failed";//$serviceTransaction->narration;
                    $response = [
                        'transaction_ref' => $serviceTransaction->transaction_ref,
                        'transaction_status' => $serviceTransaction->status,
                        'account' => (isset($result["data"]["details"]["account"])) ? $result["data"]["details"]["account"] : $input["phone_no"],
                        'transaction_message' => $error
                    ];
                    $this->updateServiceTransaction($serviceTransaction, [
                        "user_response_payload" => json_encode($response),
                    ]);
                    return $this->badRequestAlert("$error");
            }

        } catch (\Exception $exception) {
            return $this->serverErrorAlert("error", $exception);
        }

        return $this->badRequestAlert("Unknown error");
    }


    public function updateServiceTransaction(BillTransaction $serviceTransaction, array $data)
    {
        $serviceTransaction->update($data);

        if (!empty($data["user_request_payload"]) or
            !empty($data["user_response_payload"]) or
            !empty($data["processor_request_payload"]) or
            !empty($data["processor_response_payload"]) or
            !empty($data["requery_payload"])
        ) {
            $updateData = Arr::only($data, [
                "user_request_payload",
                "user_response_payload",
                "processor_request_payload",
                "processor_response_payload",
                "requery_payload"
            ]);
            $serviceTransaction->serviceTransactionLog()->update($updateData);

        }
    }

}
