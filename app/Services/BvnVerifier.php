<?php 
namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class BvnVerifier 
{
    use ApiResponse;

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('base_url');
    }
}