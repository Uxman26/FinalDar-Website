@extends('layouts.app')
@section('content')

@push('title')
<title>@lang('Add User')</title>
@endpush
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('add_user'))
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    // Intercept the form submission
    $('#client-form').submit(function(e) {
      e.preventDefault();
      $('#loader').removeClass('d-none');
      $('#form-btn-text').addClass('d-none');
      var formData = new FormData(this);
      $.ajax({
        url: "{{ url('/admin/create-user') }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          toastr.success('User Added Successfully!');
          // You may want to redirect or reset the form here
        },
        error: function() {
          toastr.error('An error occurred.');
        },
        complete: function() {
          $('#loader').addClass('d-none');
          $('#form-btn-text').removeClass('d-none');
        }
      });
    });
  });
</script>


<div class="container-fluid p-4">
  <div class="card mb-4">
    <div class="card-header">@lang('Add User')</div>
    <div class="card-body">
      <div class="breadcrumb-area">
        <h3 class="title">@lang('Add User')</h3>
        <ul class="breadcrumb">
          <li>
            <a href="{{url('/user/dashboard')}}">@lang('Dashboard')</a>
          </li>
          <li>@lang('Add User')</li>
        </ul>
      </div>

      @if(session()->has('success'))
      <div>
        <h5 class="text-success text-center mb-2">{{session()->get('success')}}</h5>
      </div>
      @endif

      <form id="client-form" class="row gy-4 needs-validation" novalidate>
        @csrf
        @foreach(['user_name', 'password', 'password_confirmation', 'phone', 'email'] as $field)
        <div class="col-md-6">
          <label for="{{ $field }}" class="form-label">@lang(ucfirst(str_replace('_', ' ', $field)))</label>
          <input type="{{ $field === 'password' || $field === 'password_confirmation' ? 'password' : 'text' }}" id="{{ $field }}" name="{{ $field }}" class="form-control" required>
          @error($field)
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        @endforeach

        <div class="col-12">
          <button class="btn btn-primary" type="submit">
            <span id="form-btn-text">@lang('Add')</span>
            <div id="loader" class="spinner-border text-light d-none" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </button>
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