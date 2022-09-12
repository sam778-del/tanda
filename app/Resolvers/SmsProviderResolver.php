<?php


namespace App\Resolvers;


use App\Classes\Termii;

class SmsProviderResolver
{

    public function initiate(): Termii
    {
        return new Termii();
    }
}
