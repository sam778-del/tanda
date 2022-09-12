<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Jobs\Web\AppCodeJob;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Imports\EmployeeImport;
use App\Models\Organization;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\Batch;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('employer');
    }

    public function addSingleEmployee(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'job_type' => 'required|string|max:100',
                'department' => 'required|string|max:100',
                'employment_type' => 'required|string|max:100',
                'phone_no' => 'required|numeric'
            ]);

            if($validator->fails())
            {
                return response()->json(["errors" => $validator->errors()], 403);
            }

            $data = $request->all();
            $data['employer_id'] = $request->user()->id;
            $data['app_code'] = $this->appCode($request->user()->id);
            $employee = Employee::create($data);

            $companyName = Organization::where('user_id', $request->user()->id)->first(['organization_name']);
            if($companyName->exists()){
                ///send app code to employee
                dispatch(new AppCodeJob($data['app_code'], $employee, $companyName->organization_name));
            }
            return response()->json($employee, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function appCode(int $userID)
    {
        $numberOfDigits = 6;
        $activationCode = substr(str_shuffle("0123456789".$userID), 0, $numberOfDigits);
        return $activationCode;
    }

    public function salaryDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'basic_pay' => 'required|numeric|min:1',
            'housing_allowance' => 'required|numeric|min:1',
            'transport_allowance' => 'required|numeric|min:1',
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $employee = Employee::find($request->id);
        if(!$employee->exists())
        {
            abort(404, "Could not find employee");
        }
        $employee->basic_pay = $request->input('basic_pay');
        $employee->housing_allowance = $request->input('housing_allowance');
        $employee->transport_allowance = $request->input('transport_allowance');
        $employee->save();
        return response()->json(["employee" => $employee, "message" => 'Information saved succesfully'], 200);
    }

    public function importFile(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'file' => "required|file|mimes:csv,xlsx|max:2000"
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        try {
            $file = Excel::import(new EmployeeImport, $request->file('file'));
            return response()->json("file imported", 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function readyImport()
    {
        $batches = Batch::orderBy('id', 'DESC')->get();
        return response()->json($batches, 200);
    }

    public function importSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id.*' => 'required|numeric',
        ], [
            'id.*.required' => 'The user id is required',
            'id.*.required.numeric' => 'The user id must be a number'
        ]);

        if($validator->fails())
        {
            return response()->json(["errors" => $validator->errors()], 403);
        }

        $Ids = $request->id;
        foreach ($Ids as $key => $item) {
            $batch = Batch::find($item);
            if(!is_null($batch))
            {
                $user = Employee::where(function($query) use ($batch) {
                    $query->where('email', $batch->email)
                    ->orWhere('phone_no', $batch->phone_no);
                })->first();

                if(is_null($user))
                {
                    $data = $batch->toArray();
                    $data['employer_id'] = $request->user()->id;
                    $data['app_code'] = $this->appCode($request->user()->id);
                    $employee = Employee::create($data);
                    $companyName = Organization::where('user_id', $request->user()->id)->first(['organization_name']);
                    if($companyName->exists()){
                        ///send app code to employee
                        dispatch(new AppCodeJob($data['app_code'], $employee, $companyName->organization_name));
                    }
                }
            }
        }
        //drop import
        Batch::query()->delete();
        return response()->json('Employee imported succesfully!', 200);
    }

    public function getAllEmployees()
    {
        $employee = Employee::orderBy('id', 'DESC')->get();
        $data = [
            'list' => $employee,
            'active' => $this->getActiveEmployee(),
            'terminated' => $this->getTerminatedEmployee()
        ];
        return $data;
    }

    public function getActiveEmployee()
    {
        return Employee::where(function($query) {
            $query->where('status', Employee::DISABLE)
            ->orWhere('status', Employee::ENABLE);
        })->count();
    }

    public function getTerminatedEmployee()
    {
        return Employee::where(function($query) {
            $query->where('status', Employee::TERMINATED);
        })->count();
    }

    public function getActiveEmployeeList()
    {
        $employee = Employee::where(function($query) {
                $query->where('status', Employee::DISABLE)
                ->orWhere('status', Employee::ENABLE);
            })->get();

        $data = [
            'list' => $employee,
            'active' => $this->getActiveEmployee(),
            'terminated' => $this->getTerminatedEmployee()
        ];
        return response()->json($data, 200);
    }

    public function getTerminatedEmployeeList()
    {
        $employee = Employee::where(function($query) {
                $query->where('status', Employee::TERMINATED);
            })->get();

        $data = [
            'list' => $employee,
            'active' => $this->getActiveEmployee(),
            'terminated' => $this->getTerminatedEmployee()
        ];
        return response()->json($data, 200);
    }

    public function searchEmployeeList(Request $request)
    {
        try {
            $search = $request->input('search');
            $employee = Employee::where(function($query) use ($search){
                $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'like', "%{$search}%")
                ->orWhere('job_type', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_no', 'like', "%{$search}%");
            })->get();

            $data = [
                'list' => $employee,
                'active' => $this->getActiveEmployee(),
                'terminated' => $this->getTerminatedEmployee()
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function index() 
    {
        $employees = $this->getAllEmployees();
        switch (count($employees['list'])) {
            case 0:
                return view('frontend.employee.onboarding', compact('employees'));  
                break;
            
            default:
                return view('frontend.employee.index', compact('employees'));  
                break;
        }
 
    }
}
