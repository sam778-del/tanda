<?php

namespace App\Http\Controllers\Okra;

use App\Http\Controllers\Controller;
use App\Services\OkraService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class OkraController extends Controller
{
    use ApiResponse;
    private OkraService $okraService;

    /**
     * OkraController constructor.
     * @param OkraService $okraService
     */
    public function __construct(OkraService $okraService)
    {
        $this->okraService = $okraService;
    }


    public function webhook(Request $request)
    {
        Log::info(print_r($request->toArray(), true));
        $response = $request->toArray();
        //AUTH
        if (Arr::get($response, 'method') == 'AUTH' && Arr::get($response, 'status') == 'is_success') {
            $okraData = $this->okraService->customerAuthenticate($response);
            if ($okraData['status']) {
                return http_response_code(200);
            }
            return http_response_code(400);
        }
        return http_response_code(200);
    }
}
