<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;


class LoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
      return redirect('login');
    }

    public function login(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email'   => 'required|email',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        //--- Validation Section Ends
      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, "status" => 1])) {
        // if successful, then redirect to their intended location
        return redirect()->intended('/dashboard');
      }
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->with('error', 'Credentials Doesn\'t Match !');
    }

    public function showForgotForm()
    {
      return view('admin.forgot');
    }

    public function forgot(Request $request)
    {
      $gs = Generalsetting::findOrFail(1);
      $input =  $request->all();
      if (Admin::where('email', '=', $request->email)->count() > 0) {
      // user found
      $admin = Admin::where('email', '=', $request->email)->firstOrFail();
      $autopass = str_random(8);
      $input['password'] = bcrypt($autopass);
      $admin->update($input);
      $subject = "Reset Password Request";
      $msg = "Your New Password is : ".$autopass;
      if($gs->is_smtp == 1)
      {
          $data = [
                  'to' => $request->email,
                  'subject' => $subject,
                  'body' => $msg,
          ];

          $mailer = new GeniusMailer();
          $mailer->sendCustomMail($data);
      }
      else
      {
          $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
          mail($request->email,$subject,$msg,$headers);
      }
      return response()->json('Your Password Reseted Successfully. Please Check your email for new Password.');
      }
      else{
      // user not found
      return response()->json(array('errors' => [ 0 => 'No Account Found With This Email.' ]));
      }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
