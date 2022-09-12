<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employer');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_name' => 'required|unique:organizations,organization_name',
            'organization_size' => 'required|string',
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $organization = Organization::where('user_id', $request->user()->id);
        if($organization->exists())
        {
            $organization->delete();
        }
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        Organization::create($data);
        return response()->json('Organization created successfully', 200);
    }
}
