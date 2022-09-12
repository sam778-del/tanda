<?php


namespace Modules\Savings\Services;


use App\Traits\ApiResponse;
use Modules\Admin\Models\SmartLockDuration;

class SmartLockService
{
    use ApiResponse;

    public function fetchSmartLockDuration()
    {
        return $this->ok(SmartLockDuration::where('status', SmartLockDuration::STATUS['Active'])->get());
    }
}
