<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\Admin;

class RoleController extends Controller
{
    //view role
    public function index(){
        $users = Admin::all();
        $roles = Role::all();
        $permissions = Permission::all();
        return view('adminDashboard.role',[
            'users'=>$users,
            'permissions'=>$permissions,
            'roles'=>$roles,
        ]);
    }

     // add permission
     function add_permission(Request $request){
        $request->validate([
            'permission_name'=>'required',
        ]);
        $permission = Permission::create(['name' => $request->permission_name]);
        return back()->with('add_permission', 'Permission Added Successfully');
    }
    
    //add role
    function add_role(Request $request){
        $request->validate([
            'role_name'=>'required',
            'permission'=>'required',
        ]);
        $role = Role::create([
            'name' => $request->role_name,
            
        ]);
       // $role->givePermissionTo($request->permission);
       $permissions = $request->input('permission');
       //dd($permissions);
       if( !empty($permissions) ){
           $role->syncPermissions($permissions);
       }
        //dd($role);
        return back()->with('add_role', 'Role Added Successfully');
    }

    //assaign role
    function assaign_role(Request $request){
        $request->validate([
            'user_id'=>'required',
            'role_id'=>'required',
        ]);
        $user = Admin::find($request->user_id);
        $user->assignRole($request->role_id);
        return back()->with('assaign_role','Assaign Role Added Successfully');
    }

    //edit permissions
    function edit_permissions($user_id){
        $permissions = Permission::get();
        $user_info = Admin::find($user_id);
        return view('adminDashboard.edit',[
            'permissions'=>$permissions,
            'user_info'=>$user_info,
        ]);
    }

    //update permission
    function update_permission(Request $request){
        $permissions = $request->input('permission');

        $user = Admin::find($request->user_id);
        if( !empty($permissions) ){
            $user->syncPermissions($permissions);
        }
        return back();
    }

    //remove role
    function remove_role($user_id){
        $user = Admin::find($user_id);
        $user->roles()->detach();
        return back()->with('delete','Role Deleted Successfully');
    }


    //edit permission
    function edit_permission($role_id){
        $role = Role::find($role_id);
        $permissions = Permission::all();
        return view('adminDashboard.edit_permission',[
            'role'=>$role,
            'permissions'=>$permissions,
        ]);
    }

    //update role permission
    function update_role_permission(Request $request){
        $role = Role::find($request->role_id);
        //$role->syncPermissions($request->permission);
        $permissions = $request->input('permission');
       //dd($permissions);
       if( !empty($permissions) ){
           $role->syncPermissions($permissions);
       }
        return back();
    }
}
