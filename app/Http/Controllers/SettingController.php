<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('adminDashboard.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $settings = Setting::firstOrNew([]);

        $settings->website_name = $request->website_name;
        $settings->tagline = $request->tagline;
        $settings->address = $request->address;
        $settings->phone_number = $request->phone_number;
        $settings->email_address = $request->email_address;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $path;
        }

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
