<?php


namespace Modules\Wallet\Services;


use App\Logic\Flutterwave;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Wallet\Factories\WalletFactory;
use Modules\Wallet\Http\Requests\AddFundRequest;
use Modules\Wallet\Models\UserCard;
use Modules\Wallet\Models\UserWallet;
use Modules\Wallet\Models\WalletHistory;

class WalletService
{
    use ApiResponse;

    public function getBalance()
    {
        $wallet = UserWallet::query()->where('user_id', auth()->user()->id)->first();
        return $this->ok($wallet);
    }

    public function fundWallet(AddFundRequest $request)
    {
        return DB::transaction(function () use ($request) {
            // Get the users default card
            return WalletFactory::create($request);
        });
    }

    public function getWalletHistory()
    {
        $history = WalletHistory::with('user')->where('user_id', auth()->user()->id)->paginate(25);
        return $this->ok($history);
    }
}
