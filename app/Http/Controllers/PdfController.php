<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Client;
use PDF;

class PdfController extends Controller
{
  public function ListingsPreview(Request $request)
  {
    $listings = Listing::join('clients', 'listings.client', '=', 'clients.id')
      ->select('listings.*', 'clients.*')
      ->get();
    // print_r($listings);
  }
  public function ExportListings(Request $request)
  {
    if ($request['print-options'] === 'all-time') {
      $listings = Listing::join('clients', 'listings.client', '=', 'clients.id')
        ->select('listings.*', 'clients.*')
        ->get();;
    } else {
      $listings = Listing::whereBetween('listings.created_at', [\Carbon\Carbon::parse($request['date_start']), \Carbon\Carbon::parse($request['date_end'])])->join('clients', 'listings.client', '=', 'clients.id')
        ->select('listings.*', 'clients.*')
        ->get();
    }
    return view('pdf.listingsPdf', compact('listings'));
  }

  public function ExportClients(Request $request)
  {
    if ($request['print-options'] === 'all-time') {
      $clients = Client::get();
    } else {
      $clients = Client::whereBetween('created_at', [\Carbon\Carbon::parse($request['date_start']), \Carbon\Carbon::parse($request['date_end'])])->get();
    }
    return view('pdf.clientPdf', compact('clients'));
  }

  public function ExportListing(Request $request)
  {
    try {
      $listing = Listing::where('id', $request['id'])->first();
      $full_name = 'Guest';
      if ($listing->client !== 'Guest') {
        $client = Client::where('id', $listing->client)->first();
        $full_name = $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name;
      }
      return view('pdf.listingForm', compact('listing', 'full_name'));
    } catch (\Exception $exception) {
      return abort(500);
    }
  }
}
