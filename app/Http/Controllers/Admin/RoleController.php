<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;

class RoleController extends Controller
{
    public function __(){
        $this->middleware('auth:admin');
    }

    public function index() {
        if(auth()->user()->can('Manage Role'))
        {
            $roles = Role::where('name', '!=', 'customer')->orderBy('id', 'DESC')->get(['id', 'name']);
            $permissions = Permission::all()->pluck('name', 'id')->toArray();
            return view('roles.index')->with(["role" => $roles, "permissions" => $permissions]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function create(Request $request){
        if(auth()->user()->can('Create Role')){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name|max:100'
            ]);

            if($validator->fails()){
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $role = new Role;
            $role->name       = $request['name'];
            $role->guard_name = 'admin';
            $role->save();

            $permissions = $request['permissions'];

            if(isset($permissions) && !empty($permissions) && count($permissions) > 0)
            {
                foreach($permissions as $permission)
                {
                    $p = Permission::where('id', '=', $permission)->firstOrFail();
                    $role->givePermissionTo($p);
                }
            }
            return redirect()->back()->with('success', __('Information Created Successfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function show($id)
    {
        if(auth()->user()->can('Edit Role'))
        {
            $permissions = Permission::all()->pluck('name', 'id')->toArray();
            $role = Role::findorfail($id);
            return view('roles.edit', compact('role', 'permissions'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit(Request $request, Role $role){
        if(auth()->user()->can('Edit Role')){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name|max:100'
            ]);

            if($validator->fails()){
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $input       = $request->except( [ 'select-all', 'permissions' ] );
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            $p_all = Permission::all();
            if(isset($p_all) && !empty($p_all) && count($p_all) > 0)
            {
                foreach($p_all as $p)
                {
                    $role->revokePermissionTo($p);
                }
            }

            if(isset($permissions) && !empty($permissions) && count($permissions) > 0)
            {
                foreach($permissions as $permission)
                {
                    $p = Permission::where('id', '=', $permission)->firstOrFail();
                    $role->givePermissionTo($p);
                }
            }
            return redirect()->route('roles.index')->with('success', __('Information Updated Successfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
}
