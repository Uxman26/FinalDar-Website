<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Login Request
    public function Login(Request $request){
        $request->validate([
          'email'=>'required',
          'password'=>'required'
        ]);

        $getUser = Admin::where('email',$request['email'])->orWhere('user_name',$request['email'])->first();
        if($getUser && $getUser->status === '1'){
            $pass = Hash::check($request['password'],$getUser->password);
           if($pass){ 
            $user = [
             'id'=>$getUser->id,
             'user_name'=>$getUser->user_name,
             'role'=>$getUser->role,
            ]; 

            $request->session()->put('user',$user);
            return redirect('/admin/dashboard');
           }else{
               session()->flash('error','Invalid Credentials!');
               return redirect()->back();
           }
        }else{
            session()->flash('error','Invalid Credentials!');
            return redirect()->back();
        }


    }

    // Register View
    public function RegisterView(){
        return view('register');
    }

    public function Register(Request $request){
        $request->validate([
            'email'=>'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);
        try{
        // Create User
        $user = new Admin();
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();
        session()->flash('success','Account Successfully Created!');
        return redirect('/login');
    }catch(Exception $e){
        return abort(500);
    }
    
    }

    public function Logout(Request $request){
        $request->session()->remove('user');
        return redirect('/');
    }

    public function ChangePassword(Request $request){
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        $new_pass = Hash::make($request['password']);
        try{
            Admin::where('email',session()->get('user')['email'])->update(['password'=>$new_pass]);
        }catch(Exception $e){
            return abort(500);
        }
        session()->flash('success',"Password Changed!");
        return redirect()->back();
    }
}
