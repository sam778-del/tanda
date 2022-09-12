<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function index() {
        $permission = Permission::orderBy('id', 'DESC')->get();
        return view('permission.index')->with(['permission' => $permission]);
    }
}
