@extends('layouts.app')
@section('content')
@push('title')
<title>@lang('Add Client')</title>
@endpush
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('add_client_manage'))
<script>
  $(document).ready(function() {
    // Intercept the form submission
    $('#client-form').submit(function() {
      // Show the loader
      $('#loader').removeClass('d-none');
      $('#form-btn-text').addClass('d-none');
    });
  });
</script>


<div class="dashborad--content">

  <div class="breadcrumb-area">
    <h3 class="title">@lang('Add Client')</h3>
    <ul class="breadcrumb">
      <li>
        <a href="{{url('/user/dashboard')}}">@lang('Dashboard')</a>
      </li>
      <li>@lang('Add Client')</li>
    </ul>
  </div>
  @if(session()->has('success'))
  <div>
    <h5 class="text-success text-center mb-2">{{session()->get('success')}}</h5>
  </div>
  @endif


  <div class="dashboard--content-item">
    <div id="request-form">
      @csrf
      <div class="profile--card">
        <form id="client-form" class="row gy-4" action="{{url('/admin/create-client')}}" method="post">
          @csrf
          <div class="col-sm-6 col-lg-4 col-xxl-4">
            <label for="name" class="form-label">@lang('First Name')</label>
            @error('first_name')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <input type="text" id="name" name="first_name" class="form-control" value="">
          </div>
          <div class="col-sm-6 col-lg-4 col-xxl-4">
            <label for="email" class="form-label">@lang('Middle Name')</label>
            @error('middle_name')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <input type="text" id="email" name="middle_name" class="form-control" value="">
          </div>
          <div class="col-sm-6 col-lg-4 col-xxl-4">
            <label for="email" class="form-label">@lang('last Name')</label>
            @error('last_name')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <input type="text" id="email" name="last_name" class="form-control" value="">
          </div>

          <div class="col-sm-6 col-xxl-4">
            <label for="phone" class="form-label">@lang('Phone')</label>
            @error('phone')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <div class="input-group">
              <input type="text" name="phone" id="phone" class="form-control" value="">
            </div>
          </div>
          <div class="col-sm-6 col-xxl-4">
            <label for="phone" class="form-label">@lang('Email')</label>
            @error('email')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <div class="input-group">
              <input type="text" name="email" id="phone" class="form-control" value="">
            </div>
          </div>
          <div class="col-sm-6 col-xxl-4">
            <label for="phone" class="form-label">@lang('Address')</label>
            @error('address')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <div class="input-group">
              <input type="text" name="address" id="phone" class="form-control" value="">
            </div>
          </div>

          <div class="col-sm-6 col-xxl-4">
            <label for="phone" class="form-label">@lang('Is Client VIP')?</label>
            @error('is_vip')
            <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>
            @enderror
            <div class="input-group">
              <select name="is_vip" class="form-control">
                <option value="no">@lang('No')</option>
                <option value="yes">@lang('Yes')</option>
              </select>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="text-end">
              <button type="submit" class="cmn--btn"><span id="form-btn-text" class="">@lang('Add')</span>
                <div id="loader" class="d-none">
                  <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@else
<div class="container-fluid p-4">
    <p>You don't have permission to view this page</p>
</div>
@endif

@endsection