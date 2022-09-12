<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Entity;
use App\Models\Organization;
use App\Models\PersonalDetails;
use App\Models\PaySchedule;
use Carbon\Carbon;

class EntityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employer');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity_name' => 'required|string|max:500',
            'entity_type' => 'required|string|max:500',
            'reg_number' => 'required|max:500',
            'vat_id' => 'max:500',
            'street' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string'
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $entity = Entity::where('user_id', $request->user()->id);
        if($entity->exists())
        {
            $entity->delete();
        }
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        Entity::create($data);
        return response()->json('Entity Created Successfully');
    }

    public function createPersonalDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'citizenship' => 'required|string|max:100',
            'dob' => 'required|date',
            'phone' => 'required|numeric'
        ], [
            'dob.required' => 'The date of birth field is required',
            'dob.date' => 'The date of birth must be a valida date'
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $personalDetails = PersonalDetails::where('user_id', $request->user()->id);
        if($personalDetails->exists())
        {
            $personalDetails->delete();
        }

        if(!empty( $request->input('dob') ))
        {
            $dob = date('d-m-Y', strtotime($request->dob));
            $request->merge( array( 'dob' => Carbon::parse($dob) ) );
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        PersonalDetails::create($data);
        return response()->json('Personal Details Created Successfully');
    }

    public function paySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule' => 'required|string|max:100',
            'schedule_day' => 'required|string|max:100'
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $schedule = PaySchedule::where('user_id', $request->user()->id);
        if($schedule->exists())
        {
            $schedule->delete();
        }
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        PaySchedule::create($data);
        return response()->json('Pay Schedule Created Successfully');
    }
}
