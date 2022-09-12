<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use App\Mail\CustomMail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Classes\Termii;
use Validator;


class GlobalSettingController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function emailIndex(){
        if(auth()->user()->can('Manage Email'))
        {
            $customer = User::orderBy('first_name', 'ASC')->get();
            return view('tools.email', compact('customer'));
        }else{
            return redirect()->back()->with('error', __('Permisssion Denied'));
        }
    }

    public function sendMail(Request $request){
        if(auth()->user()->can('Create Email'))
        {
            $validator = Validator::make($request->all(), [
                "customer" => Rule::requiredIf(function() use ($request) {
                    return $request->check === 'off';
                }),
                "title"      => "required|max:100",
                "description" => "required"
            ]);

            if($validator->fails())
            {
                return response()->json(["error" => true, "message" => $validator->errors()->first()], 200);
            }

            try {
                $customer = $request->input('customer');
                $description = $request->input('description');
                $title       = $request->input('title');
                if($request->check === 'on'){
                    $user = User::orderBy('id', 'DESC')->get();
                    foreach($user as $key => $us){
                        $text = str_replace('{first_name}', $us->first_name, $description);
                        $text = str_replace('{last_name}', $us->last_name, $text);
                        $text = str_replace('{email}', $us->email, $text);
                        Mail::to($us->email)->send(new CustomMail($title, $text));
                    }
                }
                foreach($customer as $key => $us){
                    $user = User::findorfail($us);
                    $text = str_replace('{first_name}', $user->first_name, $description);
                    $text = str_replace('{last_name}', $user->last_name, $text);
                    $text = str_replace('{email}', $user->email, $text);
                    Mail::to($user->email)->send(new CustomMail($title, $text));
                }
                return response()->json(["error" => false, "message" => 'Mail Sent Succesfully'], 200);
            } catch (\Exception $e) {
                return response()->json(["error" => true, "message" => $e->getMessage()], 200);
            }
        }else{
            return response()->json(["error" => true, "message" => __("Permission Denied")], 200);
        }
    }

    public function smsIndex(){
        if(auth()->user()->can('Manage Sms'))
        {
            $customer = User::orderBy('first_name', 'ASC')->get();
            return view('tools.sms', compact('customer'));
        }else{
            return redirect()->back()->with('error', __('Permisssion Denied'));
        }
    }

    public function sendSms(Request $request)
    {
        if(auth()->user()->can('Create Sms'))
        {
            $validator = Validator::make($request->all(), [
                "customer" => Rule::requiredIf(function() use ($request) {
                    return $request->check === 'off';
                }),
                "title"      => "required|max:100",
                "description" => "required"
            ]);

            if($validator->fails())
            {
                return response()->json(["error" => true, "message" => $validator->errors()->first()], 200);
            }

            try {
                $termii = new Termii();
                $customer = $request->input('customer');
                $description = $request->input('description');
                $title       = $request->input('title');
                if($request->check === 'on'){
                    $user = User::orderBy('id', 'DESC')->get();
                    foreach($user as $key => $us){
                        $text = str_replace('{first_name}', $us->first_name, $description);
                        $text = str_replace('{last_name}', $us->last_name, $text);
                        $text = str_replace('{email}', $us->email, $text);
                        $termii->send($us->phone_no, $text, $us);
                    }
                }
                foreach($customer as $key => $us){
                    $user = User::findorfail($us);
                    $text = str_replace('{first_name}', $user->first_name, $description);
                    $text = str_replace('{last_name}', $user->last_name, $text);
                    $text = str_replace('{email}', $user->email, $text);
                    $termii->send($user->phone_no, $text, $user);
                }
                return response()->json(["error" => false, "message" => 'Sms Sent Succesfully'], 200);
            } catch (\Exception $e) {
                return response()->json(["error" => true, "message" => $e->getMessage()], 200);
            }
        }else{
            return response()->json(["error" => true, "message" => __("Permission Denied")], 200);
        }
    }

    public function notifyIndex(){
        if(auth()->user()->can('Manage Notification'))
        {
            $customer = User::orderBy('first_name', 'ASC')->get();
            return view('tools.notification', compact('customer'));
        }else{
            return redirect()->back()->with('error', __('Permisssion Denied'));
        }
    }

    public function sendNotification(Request $request){
        if(auth()->user()->can('Create Notification'))
        {
            $validator = Validator::make($request->all(), [
                "customer" => Rule::requiredIf(function() use ($request) {
                    return $request->check === 'off';
                }),
                "title"      => "required|max:100",
                "description" => "required"
            ]);

            if($validator->fails())
            {
                return response()->json(["error" => true, "message" => $validator->errors()->first()], 200);
            }

            try {
                $termii = new Termii();
                $customer = $request->input('customer');
                $description = $request->input('description');
                $title       = $request->input('title');
                if($request->check === 'on'){
                    $user = User::orderBy('id', 'DESC')->get();
                    foreach($user as $key => $us){
                        $text = str_replace('{first_name}', $us->first_name, $description);
                        $text = str_replace('{last_name}', $us->last_name, $text);
                        $text = str_replace('{email}', $us->email, $text);
                        $termii->send($us->phone_no, $text, $us);
                    }
                }
                foreach($customer as $key => $us){
                    $user = User::findorfail($us);
                    $text = str_replace('{first_name}', $user->first_name, $description);
                    $text = str_replace('{last_name}', $user->last_name, $text);
                    $text = str_replace('{email}', $user->email, $text);
                    $termii->send($user->phone_no, $text, $user);
                }
                return response()->json(["error" => false, "message" => 'Sms Sent Succesfully'], 200);
            } catch (\Exception $e) {
                return response()->json(["error" => true, "message" => $e->getMessage()], 200);
            }
        }else{
            return response()->json(["error" => true, "message" => __("Permission Denied")], 200);
        }
    }
}

