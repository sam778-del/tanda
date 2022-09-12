<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employer');
    }

    public function personalDetails(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $employer = $request->user();
        $employer->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name
        ]);
        return response()->json('Employer Information Updated', 200);
    }

    public function getPersonalDetails(Request $request)
    {
        $employer = $request->user();
        return response()->json([
            "first_name" => $employer->first_name,
            "last_name" => $employer->last_name,
            "email" => $employer->email
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'password' => 'required|string|confirmed',
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $employer = $request->user();
        $employer->update([
            'password' => $request->password,
        ]);
        return response()->json('Employer Information Updated', 200);
    }

    public function changeAddress()
    {

    }

    public function officePhone()
    {

    }
}
