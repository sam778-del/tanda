<?php


namespace Modules\Bills\Processors\Airvend;


use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Modules\Bills\Services\AirtimeServiceOld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Bills\Interfaces\IService;
use Modules\Bills\Models\Bill;
use Modules\Bills\Models\BillTransaction;

class AirvendService
{
    use ApiResponse;

    public function makePurchase($endpoint, array $requestData): array
    {
        $headers = $this->getHeaderParams($requestData);
        $result = Http::withHeaders($headers)
            ->post($endpoint, $requestData);
        logger($result);
        if ($result->successful() and Str::lower($result->json()["confirmationMessage"]) == "ok") {
            $response = $this->ok($result, Arr::get($result["details"], 'message', 'success'));
        } else {
            $response = $this->bad(Arr::get($result["details"], 'message', 'error'), $result);
        }
        return $response;
    }


    /**
     * @throws \Exception
     */
    public function getPurchaseRequestData(array $request, IService $serviceClass, BillTransaction $serviceTransaction): array
    {
        $input = $request;
        $service = $this->serviceRepo->getServiceById($input["service_id"]);
        $serviceType = get_class($serviceClass);
        switch ($serviceType) {
            case AirtimeServiceOld::class:
                return [
                    "details" => [
                        "ref" => $serviceTransaction->transaction_ref,
                        "account" => $input["phone_no"],
                        "type" => $serviceTransaction->service->type_id,
                        "networkid" => $serviceTransaction->service->network_id,
                        "amount" => $input["amount"]
                    ]
                ];
                break;
            default:
                throw new \Exception("Invalid service type");


        }

    }

    public function getEpinServiceData(array $request, Bill $service)
    {
        if (!empty($service->network_id)) {
            return [
                "networkid" => $service->network_id,
            ];
        } else {
            return [
                "code" => $request["code"]
            ];
        }
    }


    public function getDatabundleProducts(Request $request): array
    {
        $endpoint = config("airvend.services.get_products");
        $service = $this->serviceRepo->getServiceById($request->input("service_id"));
        $requestData = [
            "details" => [
                "type" => $service->type_id,
                "networkid" => $service->network_id
            ]
        ];
        $headers = $this->getHeaderParams($requestData);
        $res = Http::withHeaders($headers)
            ->post($endpoint, $requestData);
        $result = $res->json();

        if (!empty($result) and Str::lower($result["confirmationMessage"]) == "ok") {
            $response = [
                "status" => "success",
                "message" => "Successful",
                "data" => $result["details"]["message"]
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => (isset($result["confirmationMessage"])) ? $result["confirmationMessage"] : "Error",
                "data" => $result
            ];
        }
        return $response;
    }

    public function getCableTvProducts(Request $request)
    {
        $endpoint = config("airvend.services.get_products");
        $service = $this->serviceRepo->getServiceById($request->input("service_id"));
        $requestData = [
            "details" => [
                "type" => $service->type_id,
                //"networkid" => $service->network_id
            ]
        ];
        $headers = $this->getHeaderParams($requestData);
        $res = Http::withHeaders($headers)
            ->post($endpoint, $requestData);
        $result = $res->json();

        if (!empty($result) and Str::lower($result["confirmationMessage"]) == "ok") {
            $response = [
                "status" => "success",
                "message" => "Successful",
                "data" => $result["details"]["message"]
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => (isset($result["confirmationMessage"])) ? $result["confirmationMessage"] : "Error",
                "data" => $result
            ];
        }
        return $response;
    }


    public function getEpinProducts(Request $request)
    {
        $endpoint = config("airvend.services.get_products");
        $service = $this->serviceRepo->getServiceById($request->input("service_id"));
        $requestData = [
            "details" => [
                "type" => $service->type_id,
                "networkid" => $service->network_id
            ]
        ];
        $headers = $this->getHeaderParams($requestData);
        $res = Http::withHeaders($headers)
            ->post($endpoint, $requestData);
        $result = $res->json();

        if (!empty($result) and Str::lower($result["confirmationMessage"]) == "ok") {
            $response = [
                "status" => "success",
                "message" => "Successful",
                "data" => $result["details"]["message"]
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => (isset($result["confirmationMessage"])) ? $result["confirmationMessage"] : "Error",
                "data" => $result
            ];
        }
        return $response;
    }

}
