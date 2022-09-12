<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function __construct()
    {
        $path = public_path('storage');
        if(!file_exists($path)){
            \Artisan::call('storage:link');
        }
        $this->middleware('auth:admin');
    }

    public function index()
    {
        if(auth()->user()->can('Manage User'))
        {
            $admin = Admin::orderBy('id', 'DESC')->get();
            $role = Role::pluck('name', 'id');
            return view('user.index', compact('admin', 'role'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function create(Request $request)
    {
        if(auth()->user()->can('Create User'))
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:120',
                'email' => 'required|email|max:100|unique:admins,email,NULL',
                'role' => 'required|numeric',
                'password' => 'required|min:4|confirmed',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $admin = new Admin;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->role_id = $request->role;
            $admin->save();

            $role_r = Role::where('id', $request->role)->firstorFail();
            if(!empty($role_r))
            {
                $admin->assignRole($role_r);
            }else{
                return redirect()->back()->with('error', __('role cannot be assigned to ').$admin->name);
            }
            return redirect()->back()->with('success', __('Staff Created Succesfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function edit(Request $request, Admin $admin)
    {
        if(auth()->user()->can('Edit User'))
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:120',
                'email' => 'required|email|max:100',
                'role' => 'required|numeric',
                'password' => 'required|min:4|confirmed',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->role_id = $request->role;
            $admin->save();

            $role_r = Role::where('id', $request->role)->firstorFail();
            if(!empty($role_r))
            {
                $admin->assignRole($role_r);
            }else{
                return redirect()->back()->with('error', __('role cannot be assigned to ').$admin->name);
            }
            return redirect()->back()->with('success', __('Staff Updated Succesfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function profileUpdate(){
        if(auth()->user()->can('Edit Profile')){
            $user_id = auth()->user()->id;
            $user = Admin::findorfail($user_id);
            return view('user.profile')->with(["user" => $user]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function uploadProfile(Request $request, $id){
        $user = Admin::findorfail($id);
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

            if(asset(Storage::exists($user->photo)))
            {
                asset(Storage::delete($user->photo));
            }

            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path            = $request->file('file')->storeAs('admin', $fileNameToStore);
            $user['photo']  = $path;
        }
        $user->save();
        return response()->json(['success' => $extension], 200);
    }

    public function editProfile(Request $request, Admin $user){
        if(auth()->user()->can('Edit Profile'))
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:admins,email,except,id',
                'password' => 'required|min:4|confirmed',
            ]);

            if($validator->fails()){
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $user->name  = $request->name;
            $user->email      = $request->email;
            $user->password   = bcrypt($request->password);
            $user->save();
            return redirect()->back()->with('success', __('User Information Updated'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function changeStatus(Admin $admin){
        if(auth()->user()->can('Edit User')){
            $status = $admin->status == 1 ? Admin::DISABLE : Admin::ENABLE;
            $admin->status = $status;
            $admin->save();
            return redirect()->back()->with('success', __('User Status Changed Succesfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function deleteUser($admin)
    {
        if(auth()->user()->can('Delete User')){
            $user = Admin::findorfail($admin);
            if(asset(Storage::exists($user->photo))){
                asset(Storage::delete($user->photo));
            }
            $user->delete();
            return redirect()->back()->with('success', __('User Deleted Succesfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
}
