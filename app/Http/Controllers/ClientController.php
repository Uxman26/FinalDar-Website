<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
  public function CreateClient(Request $request)
  {
    $request->validate([
      'first_name' => 'required',
      'middle_name' => 'required',
      'last_name' => 'required',
      'phone' => 'required',
      'address' => 'required',
      'email' => 'required',
      'is_vip' => 'required',
    ]);

    try {
      $client = new Client();
      $client->first_name = $request['first_name'];
      $client->middle_name = $request['middle_name'];
      $client->last_name = $request['last_name'];
      $client->phone = $request['phone'];
      $client->address = $request['address'];
      $client->email = $request['email'];
      $client->is_vip = $request['is_vip'];
      $client->save();
      session()->flash('success', 'Client Successfully Created!');
      return redirect('/admin/manage-clients');
    } catch (\Exception $e) {
      return abort(500);
    }
  }

  public function UpdateClient(Request $request)
  {
    $request->validate([
      'id' => 'required',
      'first_name' => 'required',
      'middle_name' => 'required',
      'last_name' => 'required',
      'phone' => 'required',
      'address' => 'required',
      'email' => 'required',
      'is_vip' => 'required',
    ]);

    try {
      Client::where('id', $request['id'])->update([
        'first_name' => $request['first_name'],
        'middle_name' => $request['middle_name'],
        'last_name' => $request['last_name'],
        'phone' => $request['phone'],
        'address' => $request['address'],
        'email' => $request['email'],
        'is_vip' => $request['is_vip'],
      ]);

      session()->flash('success', 'Client Successfully Updated!');
      return redirect('/admin/manage-clients');
    } catch (\Exception $e) {
      return abort(500);
    }
  }

  public function DeleteClient(Request $request)
  {
    $request->validate([
      'id' => 'required'
    ]);

    try {
      Client::where('id', $request['id'])->delete();
      session()->flash('success', 'Client Successfully Deleted!');
      return redirect('/admin/manage-clients');
    } catch (\Exception $exception) {
      return abort(500);
    }
  }

  public function GetClients(Request $request)
  {

    $search = $request['search'] ?? '';
    $filter = $request['filter'];

    try {
      $users = Client::where($filter, 'LIKE', '%' . $search . '%')->get();
      return response()->json(['users' => $users]);
    } catch (\Exception $exception) {
      return abort(500);
    }
  }
}
