<?php


namespace App\Interfaces;


interface ISms
{
    public function send(string $phoneNo, string $message);

    public function logNotification(array $data);

}
