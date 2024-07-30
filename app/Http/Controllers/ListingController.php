<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
   public function CreateListing(Request $request)
   {
      $request->validate([
         'client' => 'required',
         'serial_no' => 'required',
         'amount' => 'required',
         'status' => 'required',
         'title' => 'required',
         'size' => 'required',
         'type' => 'required',
         'land_type' => 'required',

      ]);
      try {
         $listing = new Listing($request->all());
         $listing->media = json_encode($request->input('media', []));  // Ensure media is encoded correctly
         $listing->created_by = $request->session()->get('user')['user_name'];
         $listing->updated_by = $request->session()->get('user')['user_name'];
         $listing->save();

         session()->flash('success', 'Listing Successfully Created!');
         return response()->json(['message' => 'Listing Successfully Created!'], 200);
      } catch (\Exception $exception) {
         Log::error("An error occurred: " . $exception->getMessage());
         return abort(500);
      }
   }
   public function filterListings(Request $request)
   {
       try {
           $query = Listing::query();
   
           // Apply filters based on request input
           if ($request->input('serial_no')) {
               $query->where('serial_no', 'like', '%' . $request->input('serial_no') . '%');
           }
           if ($request->input('title')) {
               $query->where('title', 'like', '%' . $request->input('title') . '%');
           }
           if ($request->input('min_size')) {
               $query->where('size', '>=', $request->input('min_size'));
           }
           if ($request->input('max_size')) {
               $query->where('size', '<=', $request->input('max_size'));
           }
           if ($request->input('status')) {
               $query->where('status', $request->input('status'));
           }
           if ($request->input('type')) {
               $query->where('type', $request->input('type'));
           }
           if ($request->input('land_type')) {
               $query->where('land_type', $request->input('land_type'));
           }
           if ($request->input('min_amount')) {
               $query->where('amount', '>=', $request->input('min_amount'));
           }
           if ($request->input('max_amount')) {
               $query->where('amount', '<=', $request->input('max_amount'));
           }
           if ($request->input('no_bedrooms')) {
               $query->where('no_bedrooms', $request->input('no_bedrooms'));
           }
           if ($request->input('no_toilets')) {
               $query->where('no_toilets', $request->input('no_toilets'));
           }
           if ($request->input('no_majlis')) {
               $query->where('no_majlis', $request->input('no_majlis'));
           }
           if ($request->input('no_floors')) {
               $query->where('no_floors', $request->input('no_floors'));
           }
           if ($request->input('no_kitchens')) {
               $query->where('no_kitchens', $request->input('no_kitchens'));
           }
   
           $listings = $query->get();
   
           return view('adminDashboard.listing_table', compact('listings'));
       } catch (\Exception $e) {
           Log::error("An error occurred while filtering listings: " . $e->getMessage());
           session()->flash('error', 'An error occurred while filtering the listings.');
           return back();
       }
   }
      
   public function UpdateListing(Request $request)
   {
      $request->validate([
         'client' => 'required',
         'serial_no' => 'required',
         'title' => 'required',
         'size' => 'required',
         'amount' => 'required',
         'status' => 'required',
         'type' => 'required',
         'land_type' => 'required',
         'location' => 'sometimes|string',
      ]);

      try {
         $listing = Listing::findOrFail($request->input('id'));

         // Log the media input data
         Log::info('Received media data: ' . $request->input('media'));

         $listing->fill($request->all());

         // Save media data
         $mediaData = $request->input('media');
         $listing->media = $mediaData;

         $listing->updated_by = $request->session()->get('user')['user_name'];
         $listing->save();

         session()->flash('success', 'Listing Successfully Updated!');
         return redirect('/admin/manage-listings');
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
         Log::error("Listing not found with ID: " . $request->input('id'));
         return back()->withErrors(['error' => 'Listing not found']);
      } catch (\Exception $exception) {
         Log::error("An error occurred: " . $exception->getMessage());
         return abort(500, 'Internal Server Error');
      }
   }
   public function editListing($id)
   {
      $listing = Listing::findOrFail($id);
      $clients = Client::all(); // Assuming you have a Client model to fetch all clients
      return view('editListing', compact('listing', 'clients')); // Pass clients to the view
   }
   public function DeleteListing(Request $request)
   {
      $request->validate([
         'id' => 'required'
      ]);

      try {
         $listing = Listing::findOrFail($request->input('id'));
         $listing->delete();

         session()->flash('success', 'Listing Successfully Deleted!');
         return redirect('/admin/manage-listings');
      } catch (\Exception $exception) {
         Log::error("An error occurred: " . $exception->getMessage());
         return abort(500);
      }
   }
}
