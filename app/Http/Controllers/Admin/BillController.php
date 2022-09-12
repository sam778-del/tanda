<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillPayment;
use App\Models\BillTransaction;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function payment()
    {
        $billPayment = BillPayment::orderBy('id', 'DESC')->get();
        return view('bill.payment', compact('billPayment'));
    }

    public function transaction()
    {
        $billTransaction = BillTransaction::orderBy('id', 'DESC')->get();
        return view('bill.transaction', compact('billTransaction'));
    }

    public function userBill($user_id)
    {
        $billTransaction = BillTransaction::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
        return view('bill.user', compact('billTransaction'));
    }
}
