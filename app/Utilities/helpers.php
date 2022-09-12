<?php

use App\Models\User;

if (! function_exists("getUser")) {
    function getUser() {
        /** @var User $user */
        $user = auth()->user();
        return $user;
    }
}

