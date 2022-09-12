<?php


namespace App\Repositories;


use App\Models\TandaNotification;

class TandaNotificationRepo
{

    public function create(array $data) : TandaNotification
    {
        return TandaNotification::create($data);
    }

}
