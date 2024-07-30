@extends('layouts.app')

@section('content')
@push('title')
<title>@lang('Client Details')</title>
@endpush
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('view_client_manage'))

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    // Intercept the form submission
    $('#client-form').submit(function(event) {
      event.preventDefault(); // Prevent the default form submission
      $('#loader').removeClass('d-none');
      $('#form-btn-text').addClass('d-none');

      var formData = new FormData(this);
      $.ajax({
        url: "{{ url('/admin/update-client') }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          toastr.success('Client Updated Successfully!');
          location.reload(); // Reload the page to see updated data
        },
        error: function(xhr) {
          $('#loader').addClass('d-none');
          $('#form-btn-text').removeClass('d-none');
          toastr.error('An error occurred. Please try again.');
        }
      });
    });
  });
</script>
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
<div class="container-fluid p-4">
  <div class="card mb-4">
    <div class="card-header">@lang('Client Details')</div>
    <div class="card-body">
      <ul>
        <li>
          <a href="{{url('/user/dashboard')}}">@lang('Dashboard')</a>
        </li>
        <li>@lang('Client Details')</li>
      </ul>
      @if(session()->has('success'))
      <div>
        <h5 class="text-success text-center mb-2">{{session()->get('success')}}</h5>
      </div>
      @endif

      <form id="client-form" class="row gy-4">
        @csrf
        <!-- Add your form fields here similar to those in ViewListingDetail -->
        <div class="col-sm-6 col-lg-4 col-xxl-4">
          <label for="first_name" class="form-label">@lang('First Name')</label>
          <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $client->first_name }}">
        </div>

        <!-- Additional fields here -->
        <div class="col-sm-6 col-lg-4 col-xxl-4">
          <label for="middle_name" class="form-label">@lang('Middle Name')</label>
          <input type="text" id="middle_name" name="middle_name" class="form-control" value="{{ $client->middle_name }}">
        </div>
        <div class="col-sm-6 col-lg-4 col-xxl-4">
          <label for="last_name" class="form-label">@lang('Last Name')</label>
          <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $client->last_name }}">
        </div>
        <div class="col-sm-6 col-xxl-4">
          <label for="phone" class="form-label">@lang('Phone')</label>
          <input type="text" id="phone" name="phone" class="form-control" value="{{ $client->phone }}">
        </div>
        <div class="col-sm-6 col-xxl-4">
          <label for="email" class="form-label">@lang('Email')</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ $client->email }}">
        </div>

        <div class="col-sm-12">
          <div class="text-end">
            <button type="submit" class="btn btn-primary">
              <span id="form-btn-text">@lang('Update')</span>
              <div id="loader" class="d-none spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </button>
          </div>
        </div>
      </form>

      <div>
        <h4>@lang('Client Listings')</h4>
        <!-- Listing of the client's properties or related data -->
        <table class="table bg--body">
          <thead>
            <tr>
              <th>@lang('Property ID')</th>
              <th>@lang('Property Title')</th>
              <th>@lang('Property Type')</th>
              <th>@lang('Date Listed')</th>
              <th>@lang('Actions')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($client->listings as $listing)
            <tr>
              <td>{{ $listing->id }}</td>
              <td>{{ $listing->title }}</td>
              <td>{{ $listing->type }}</td>
              <td>{{ $listing->created_at->format('d M Y') }}</td>
              <td>
                <a href="{{ url('/admin/view-listing', $listing->id) }}" class="btn btn-primary">@lang('View')</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @if($client->listings->isEmpty())
        <div class="alert alert-info">@lang('No listings found for this client.')</div>
        @endif
      </div>
    </div>
  </div>
</div>
@else
<div class="container-fluid p-4">
    <p>You don't have permission to view this page</p>
</div>
@endif
@endsection