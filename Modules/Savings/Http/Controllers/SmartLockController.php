<?php


namespace Modules\Savings\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Models\SmartLockDuration;
use Modules\Savings\Http\Requests\SmartLockRequest;
use Modules\Savings\Services\SmartLockService;

class SmartLockController extends Controller
{
    private SmartLockService $smartLockService;

    public function __construct(SmartLockService $smartLockService)
    {
        $this->smartLockService = $smartLockService;
    }

    public function getSmartLockDuration()
    {
        $smartLock = $this->smartLockService->fetchSmartLockDuration();
        return $this->successResponse(
            'SmartLock duration retrieved successfully',
            $smartLock->data
        );
    }

    public function createSmartLock(SmartLockRequest $request)
    {
        //TODO debit the user first before processing request

        //TODO create smart lock

        //TODO create transaction
    }
}
