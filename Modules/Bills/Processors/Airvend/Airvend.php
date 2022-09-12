<?php


namespace Modules\Bills\Processors\Airvend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Bills\Interfaces\IBillsInterface;
use Modules\Bills\Processors\Airvend\AirvendService;
use Modules\Bills\Repositories\BillRepository;

class Airvend extends AirvendService implements IBillsInterface
{

    public $url;
    public $username;
    public $password;
    public $apiKey;
    public $serviceRepo;
    public $customerPhone = "08061373790";

    public function __construct(BillRepository $serviceRepository)
    {
        $this->serviceRepo = $serviceRepository;

        $env = config("app.env") ?? "local";
        if ($env == "local") {
            $this->url = config("airvend.credentials.test.url");
            $this->username = config("airvend.credentials.test.username");
            $this->password = config("airvend.credentials.test.password");
            $this->apiKey = config("airvend.credentials.test.api_key");

        } else {
            $this->url = config("airvend.credentials.production.url");
            $this->username = config("airvend.credentials.production.username");
            $this->password = config("airvend.credentials.production.password");
            $this->apiKey = config("airvend.credentials.production.api_key");
        }

    }

    public function getCredentials()
    {
        return [
            "url" => $this->url,
            "username" => $this->username,
            "password" => $this->password
        ];
    }

    public function getBalance()
    {
        $endpoint = config("airvend.services.get_balance");
        $headers = $this->getHeaderParams([]);
        $result = Http::withHeaders($headers)
            ->post($endpoint);
        return $result->json();
    }

    public function accountVerification(array $request)
    {
        $service = $this->serviceRepo->getServiceById($request["service_id"]);
        $requestData = [
            "details" => [
                "type" => $service->type_id,
                "account" => $request["account"]
            ]
        ];
        $endpoint = config("airvend.services.verification");
        $headers = $this->getHeaderParams($requestData);
        $result = Http::withHeaders($headers)
            ->post($endpoint, $requestData);

        logger($result->object());
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

    public function getHeaderParams(array $requestData): array
    {
        $hash_key = $this->apiKey;
        $username = $this->username;
        $password = $this->password;

        $hashParameter = json_encode($requestData) . $hash_key;
        $hash = hash("sha512", $hashParameter);

        return [
            "username: $username",
            "password: $password",
            "hash: $hash"
        ];
    }


    public function getServices()
    {
        // TODO: Implement getServices() method.
    }

    public function getServiceByCategory()
    {
        // TODO: Implement getServiceByCategory() method.
    }

    public function getAirtimeServices()
    {
        // TODO: Implement getAirtimeServices() method.
    }

    public function getDataService()
    {
        // TODO: Implement getDataService() method.
    }

    public function getElectricityService()
    {
        // TODO: Implement getElectricityService() method.
    }

    public function getEpinService()
    {
        // TODO: Implement getEpinService() method.
    }

    public function getCableService()
    {
        // TODO: Implement getCableService() method.
    }
}
