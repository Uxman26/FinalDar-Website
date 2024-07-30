<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing; // Adjust this according to your model namespace

class MapController extends Controller
{
    public function index()
    {
        $listings = Listing::all();

        return view('adminDashboard.map', compact('listings'));
    }
}
