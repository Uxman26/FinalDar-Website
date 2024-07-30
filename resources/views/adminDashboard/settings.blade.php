@extends('layouts.app')
@section('content')
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('setting'))
<div class="container">
    <h1>Website Settings</h1>
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="website_name" class="form-label">Website Name</label>
            <input type="text" class="form-control" id="website_name" name="website_name" value="{{ $settings->website_name ?? '' }}" required>
        </div>
        <!-- Add other fields similar to the above, ending with the logo field -->
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo" name="logo">
            @if($settings && $settings->logo_path)
            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" style="width: 100px;">
            @endif
        </div>
        <div class="mb-3">
            <label for="tagline" class="form-label">Tagline</label>
            <input type="text" class="form-control" id="tagline" name="tagline" value="{{ $settings->tagline ?? '' }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $settings->address ?? '' }}">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $settings->phone_number ?? '' }}">
        </div>
        <div class="mb-3">
            <label for="email_address" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email_address" name="email_address" value="{{ $settings->email_address ?? '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@else
<div class="container">
    <p>You don't have permission to view this page</p>
</div>
@endif
@endsection