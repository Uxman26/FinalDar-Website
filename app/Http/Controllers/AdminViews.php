<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Client;
use App\Charts\Statistics;
use App\Charts\PieStatistics;
use App\Models\Listing;
use App\Models\Events;
use Illuminate\Http\Request;

class AdminViews extends Controller
{
  public function Dashboard()
  {
    try {
      // $total_clients = Client::count();
      $total_vip_clients = Client::where('is_vip', 'yes')->count();
      $total_org_clients = Client::where('is_vip', 'no')->count();
      $total_onsell_listings = Listing::where('status', 'On Sell')->count();
      $total_sold_listings = Listing::where('status', 'Sold')->count();
      $latest_listings = Listing::limit(4)->get();
      $latest_clients = Client::limit(4)->get();
      return view('adminDashboard.index', compact('total_sold_listings', 'total_onsell_listings', 'latest_listings', 'latest_clients', 'total_vip_clients', 'total_org_clients'));
    } catch (\Exception $exception) {
      return abort(500);
    }
  }

  public function ManageListings(Request $request)
  {

    $search = $request['search'] ?? '';
    $filter = $request['filter'];


    if ($search !== '') {

      if ($request['search'] === 'title') {
        try {
          $listings = Listing::where($filter, 'LIKE', '%' . $search . '%')->paginate(20);
        } catch (\Exception $exception) {
          return abort(500);
        }
      } else {
        try {
          $listings = Listing::where($filter, '=', $search)->paginate(20);
        } catch (\Exception $exception) {
          return abort(500);
        }
      }
    } else {
      try {
        $listings = Listing::paginate(20);
      } catch (\Exception $exception) {
        return abort(500);
      }
    }
    return view('adminDashboard.ManageListing', compact('listings', 'search'));
  }

  public function AddListing()
  {
    return view('adminDashboard.AddListing');
  }

  public function ViewListing(Request $request)
  {
    try {
      $listing = Listing::where('id', $request['id'])->first();
      $full_name = 'Guest';
      if ($listing->client !== 'Guest') {
        $client = Client::where('id', $listing->client)->first();
        $full_name = $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name;
      }
      return view('adminDashboard.ViewListingDetail', compact('listing', 'full_name'));
    } catch (\Exception $exception) {
      return abort(500);
    }
  }

  public function ManageClients(Request $request)
  {

    $search = $request['search'] ?? '';
    $filter = $request['filter'];

    if ($filter === 'yes' || $filter === 'no') {
      try {
        $clients = Client::where('is_vip', $filter)->paginate(20);
      } catch (\Exception $exception) {
        return abort(500);
      }
    } else {
      if ($search !== '') {
        try {
          $clients = Client::where($filter, $search)->paginate(20);
        } catch (\Exception $exception) {
          return abort(500);
        }
      } else {
        try {
          $clients = Client::paginate(20);
        } catch (\Exception $exception) {
          return abort(500);
        }
      }
    }
    return view('adminDashboard.ManageClient', compact('clients', 'search'));
  }


  public function AddClient()
  {
    return view('adminDashboard.AddClient');
  }

  public function ViewClient(Request $request)
  {
    $client = Client::where('id', $request['id'])->first();
    $listings = Listing::where('id', 10)->get();
    return view('adminDashboard.ViewClientDetail', compact('client', 'listings'));
  }

  public function ManageUsers(Request $request)
  {

    $search = $request['search'] ?? '';
    $filter = $request['filter'];
    if ($search !== '') {
      try {
        $users = Admin::where($filter, $search)->where('role', '0')->paginate(20);
      } catch (\Exception $exception) {
        return abort(500);
      }
    } else {
      try {
        $users = Admin::where('role', '0')->paginate(20);
      } catch (\Exception $exception) {
        return abort(500);
      }
    }
    return view('adminDashboard.ManageUsers', compact('users', 'search'));
  }

  public function AddUser()
  {
    return view('adminDashboard.AddUser');
  }

  public function ViewUser(Request $request)
  {
    $user = Admin::where('id', $request['id'])->where('role', '0')->first();
    return view('adminDashboard.ViewUserDetail', compact('user'));
  }

  public function ManageAppointments()
  {

    try {
      $events = Events::get();
      return view('adminDashboard.ManageAppointment', compact('events'));
    } catch (\Exception $exception) {
      return abort(500);
    }
  }

  public function CreateAppointment()
  {
    return view('adminDashboard.CreateAppointment');
  }

  public function ChangePassword()
  {
    return view('adminDashboard.change-password');
  }
}
