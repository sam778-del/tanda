<?php


namespace App\Repositories;


use App\Models\User;

class UserRepository
{

    public function getUserByPhoneNo($phone)
    {
        return User::where("phone", $phone)->first();
    }

    public function getUserByEmail($email)
    {
        return User::whereEmail($email)->first();
    }
}
