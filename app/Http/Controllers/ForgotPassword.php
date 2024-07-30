<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\URL;

class ForgotPassword extends Controller
{
  public function ChangePassword(Request $request)
  {
    $request->validate([
      'old-password' => 'required',
      'password' => 'required|string|confirmed',
      'password_confirmation' => 'required',
    ]);

    try {

      $id = $request->session()->get('user')['id'];

      if ($id) {
        $admin = Admin::where('id', $id)->first();
        $pass = Hash::check($request['old-password'], $admin->password);
        if ($pass) {
          $password = Hash::make($request['password']);
          Admin::where('id', $id)->update(['password' => $password]);
          return response()->json(['message' => 'Password Changed Successfully!'], 200);
        } else {
          return response()->json(['message' => 'Invalid Credentials!'], 401);
        }
      } else {
        return response()->json(['message' => 'Unauthorized!'], 401);
      }
    } catch (\Exception $exception) {
      return response()->json(['message' => 'Internal Server Error!'], 401);
    }
  }
}
