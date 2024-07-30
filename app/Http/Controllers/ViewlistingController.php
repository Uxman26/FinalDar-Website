<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing; // Adjust this namespace based on your actual Listing model location

class ViewlistingController extends Controller
{
    public function show($id)
    {
        $listing = Listing::findOrFail($id);
        return view('adminDashboard.viewlisting', compact('listing'));
    }
}
