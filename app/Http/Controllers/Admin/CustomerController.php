<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserBankAccount as BankAccount;
use App\Models\UserBankAccountTransaction as Transact;
use App\Models\BankStatement as Statement;
use Validator;

class CustomerController extends Controller
{
    public function __construct(){
        $path = public_path('storage');
        if(!file_exists($path)){
            \Artisan::call('storage:link');
        }
        $this->middleware('auth:admin');
    }

    public function index(){
        if(auth()->user()->can('Manage Customer'))
        {
            $user = User::orderBy('id', 'DESC')->get();
            return view('customer.index')->with(["customer" => $user]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function edit(Request $request, User $user){
        if(auth()->user()->can('Edit Customer'))
        {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email'
            ]);

            if($validator->fails()){
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->email      = $request->email;
            $user->save();
            return redirect()->route('customer.index')->with('success', __('User Information Updated'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function changeStatus(User $user){
        if(auth()->user()->can('Delete Customer')){
            $status = $user->status == "ENABLE" ? User::DISABLE : User::ENABLE;
            $user->status = $status;
            $user->save();
            return redirect()->back()->with('success', __('User Status Changed Succesfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function customerProfile($id){
        if(auth()->user()->can('Edit Customer')){
            $user = User::findorfail($id);
            $transaction = Transaction::orderBy('id', 'DESC')->where('user_id', $user->id)->get();
            return view('customer.profile')->with(["user" => $user, 'transaction' => $transaction]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function uploadProfile(Request $request, $id){
        $user = User::findorfail($id);
        $validator = Validator::make($request->all(), [
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        if($validator->fails())
        {
            return response()->json(['error', $validator->errors()->first()], 413);
        }

        if($request->hasFile('file'))
        {
            $validator = Validator::make(
                $request->all(), [
                    'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                ]
            );

            if($validator->fails())
            {
                return response()->json(['error', $validator->errors()->first()], 413);
            }

            if(asset(Storage::exists($user->image)))
            {
                asset(Storage::delete($user->image));
            }

            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path            = $request->file('file')->storeAs('avatar', $fileNameToStore);
            $user['image']  = $path;
        }
        $user->save();
        return response()->json(['success' => $extension], 200);
    }

    public function bankAccount($id){
        if(auth()->user()->can('Manage Bank Account'))
        {
            $bank = BankAccount::orderBy('id', 'DESC')->where('user_id', $id)->get();
            $user = User::findorfail($id);
            return view('bank.index')->with(['bank' => $bank, "user" => $user]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function bankTransactions(Request $request){
        if(auth()->user()->can('Manage Bank Statement'))
        {
            $transaction = Transact::orderBy('id', 'DESC')->where('user_bank_account_id', $request->bank_code)->get();
            return view('bank.transaction')->with(['transaction' => $transaction]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function bankStatement($user_id){
        if(auth()->user()->can('Manage Bank Statement')){
            $bank_statement = Statement::orderBy('id', 'DESC')->where('user_id', $user_id)->get();
            return view('bank.statement', compact('bank_statement'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
}
