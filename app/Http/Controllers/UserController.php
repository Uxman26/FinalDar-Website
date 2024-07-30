<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function CreateUser(Request $request)
  {
    $request->validate([
      'user_name' => 'required|unique:admins',
      'email' => 'required|unique:admins',
      'phone' => 'required',
      'password' => 'required|min:6|confirmed',
      'password_confirmation' => 'required_with:password|same:password|min:6'
    ]);

    try {
      $user = new Admin();
      $user->user_name = $request['user_name'];
      $user->phone = $request['phone'];
      $user->email = $request['email'];
      $user->password = Hash::make($request['password']);
      $user->save();
      session()->flash('success', 'User Successfully Created!');
      return redirect('/admin/manage-users');
    } catch (Exception $e) {
      return abort(500);
    }
  }

  public function UpdateUser(Request $request)
  {
    $request->validate([
      'id' => 'required',
      'phone' => 'required',
      'email' => 'required',
    ]);

    try {
      Admin::where('id', $request['id'])->update([
        'phone' => $request['phone'],
        'email' => $request['email'],
      ]);

      session()->flash('success', 'User Successfully Updated!');
      return redirect('/admin/manage-users');
    } catch (Exception $e) {
      return abort(500);
    }
  }

  public function BanUser(Request $request)
  {
    $request->validate([
      'id' => 'required',
    ]);

    try {
      Admin::where('id', $request['id'])->update([
        'status' => '0',
      ]);

      // session()->flash('success','User Successfully Banned!');
      // return redirect('/admin/manage-users');
      return response()->json(['status' => '200']);
    } catch (\Exception $exception) {
      return response()->json(['status' => '200', 'error' => error]);
    }
  }

  public function UnBanUser(Request $request)
  {
    $request->validate([
      'id' => 'required',
    ]);

    try {
      Admin::where('id', $request['id'])->update([
        'status' => '1',
      ]);

      // session()->flash('success','User Successfully UnBanned!');
      return response()->json(['status' => '200']);
    } catch (\Exception $exception) {
      return response()->json(['status' => '200', 'error' => error]);
    }
  }

  public function DeleteUser(Request $request)
  {
    $request->validate([
      'id' => 'required'
    ]);

    try {
      Admin::where('id', $request['id'])->delete();
      // session()->flash('success','User Successfully Deleted!');
      return response()->json(['status' => '200']);
    } catch (\Exception $exception) {
      return response()->json(['status' => '200', 'error' => error]);
    }
  }
}
