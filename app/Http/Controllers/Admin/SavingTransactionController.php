<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SavingTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        if(auth()->user()->can('Manage Saving Transaction'))
        {
            $transaction = DB::table('saving_wallet_transactions')->orderBy('id', 'DESC')->get();
            return view('savings.transaction', compact('transaction'));
        }else{
            redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function savingWallet($savings_wallet_id){
        if(auth()->user()->can('Manage Saving Transaction'))
        {
            $wallet = DB::table('savings_wallet')->where('id', $savings_wallet_id)->first();
            return view('savings.wallet', compact('wallet'));
        }else{
            redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function savingWalletHistory($user_id){
        if(auth()->user()->can('Manage Saving Transaction'))
        {
            $history = DB::table('savings_wallet_history')->where('user_id', $user_id)->get();
            $user    = User::findorfail($user_id);
            return view('savings.history', compact('history', 'user'));
        }else{
            redirect()->back()->with('error', __('Permission Denied'));
        }
    }
}
