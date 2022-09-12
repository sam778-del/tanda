<?php


namespace App\Classes;

use App\Interfaces\ISms;
use App\Services\TandaNotificationService;
use App\Traits\ApiCallTrait;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Termii implements ISms
{
    use ApiCallTrait, ApiResponse;

    private $apiKey;

    private $sender;

    private $baseUrl;

    private $company;
    /**
     * @var TandaNotificationService
     */
    private TandaNotificationService $tandaNotificationService;

    public function __construct()
    {
        $credential = config("tanda.sms");
        $this->sender = $credential["sender"];
        $this->apiKey = $credential["termii"]["api_key"];
        $this->baseUrl = $credential["termii"]["base_url"];
        $this->company = $credential["termii"]["company"];
        $this->tandaNotificationService = new TandaNotificationService();
    }

    /**
     * @param string $phone
     * @param string $message
     * @return array
     */
    public function send(string $phone, string $message, $user = null)
    {
        //log notification
        $notificationData = [
            "user_id" => !is_null($user) ? $user->id : auth("api")->id(),
            "type" => "sms",
            "phone_no" => $phone,
            "message" => $message
        ];
        $endPoint = $this->baseUrl . "/sms/send";
        $data = [
                "to" => $phone,
                "from" => $this->sender,
                "sms" => $message,
                "type" => "plain",
                "channel" => "dnd",
                "api_key" => $this->apiKey
            ];

        $result = Http::post($endPoint, $data)->json();

        if (!empty(Arr::get($result, 'message')) and Arr::get($result, 'message') == "Successfully Sent") {
            $message = Arr::get($result, 'message') ?? "Successfully Sent";
            return $this->ok($result, $message);
        } else {
            $message = (isset($result["message"])) ? $result["message"] : "Error sending sms";
            return $this->bad($message, $result);
        }
    }

    /**
     * @param string $sender
     * @param string $description
     * @return array
     */
    public function createSenderId(string $sender, string $description): array
    {
        $endPoint = $this->baseUrl . "sender-id/request";
        $data = [
            "api_key" => $this->apiKey,
            "sender_id" => $sender,
            "usecase" => $description,
            "company" => $this->company
        ];
        $result = $this->curlPost($endPoint, $data);
        if (!empty($result) and isset($result["code"]) and $result["code"] == "ok") {
            $message = (isset($result["message"])) ? $result["message"] : "Sms sender created successfully";
            return $this->ok($result, $message);
        } else {
            $message = (isset($result["message"])) ? $result["message"] : "Error creating Sms sender";
            return $this->bad($message, $result);
        }
    }

    /**
     * @return array
     */
    public function fetchSmsSenders(): array
    {
        $endPoint = $this->baseUrl . "sender-id";
        $data = [
            "api_key" => $this->apiKey,
        ];
        $result = $this->curlGet($endPoint, $data);
        if (!empty($result)) {
            $message = (isset($result["message"])) ? $result["message"] : "Sms sender fetched";
            return $this->ok($result, $message);
        } else {
            $message = (isset($result["message"])) ? $result["message"] : "Error fetching Sms sender";
            return $this->bad($message, $result);
        }
    }


    /**
     * @param array $data
     * @return array
     */
    public function logNotification(array $data)
    {
        if (!empty($data)) {
            return $this->tandaNotificationService->logNotification($data);
//            if ($result) {
//                return $this->ok($result);
//            } else {
//                return $this->bad();
//            }
        } else {
            abort(400, "Unable to log notification");
        }
    }
}
