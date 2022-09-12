<?php


namespace App\Services;

use App\Models\TandaNotification;
use App\Repositories\TandaNotificationRepo;
use App\Traits\ApiResponse;
use Illuminate\Http\ResponseTrait;

class TandaNotificationService
{
    use ApiResponse;
    /**
     * @var TandaNotificationRepo
     */
    private TandaNotificationRepo $tandaNotificationRepo;

    public function __construct()
    {
        $this->tandaNotificationRepo = new TandaNotificationRepo();
    }

    public function logNotification(array $data)
    {
        $tandaNotification = $this->tandaNotificationRepo->create($data);
        if ($tandaNotification) {
            return $this->ok($tandaNotification, "Notification logged");
        } else {
            return $this->bad("Error logging notification", $tandaNotification);
        }
    }
}
