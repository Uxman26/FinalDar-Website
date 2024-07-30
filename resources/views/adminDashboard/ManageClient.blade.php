@extends('layouts.app')
@section('content')
@push('title')
<title>@lang('Client Management')</title>
@endpush

<!-- <script>
  toastr.success('Success messages');
</script> -->

<!-- Delete Form Function Start -->
<script>
  function DeleteClient(id) {
    // Show the loader
    $(`#loader-${id}`).removeClass('d-none');
    $(`#form-btn-text-${id}`).addClass('d-none');

    const formData = $(`#client-form-${id}`).serialize();

    // Make an Ajax POST request
    $.ajax({
      type: 'POST',
      url: '{!! url("/admin/delete-client") !!}', // Replace with your actual endpoint
      data: formData,
      success: function(response) {
        toastr.success('Client Successfully Deleted!');
        // location.reload(true); // 'true' forces a reload from the server
        $(`#loader-${id}`).addClass('d-none');
        $(`#form-btn-text-${id}`).removeClass('d-none');
      },
      error: function(error) {
        $(`#loader-${id}`).addClass('d-none');
        $(`#form-btn-text-${id}`).removeClass('d-none');
        // Handle the error response here
      }
    });

  }
</script>

<!-- Delete Form Function End -->
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('client_manage'))
<div class="layout-page">

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="dashborad--content">
        <div class="breadcrumb-area">
          <h3 class="title">@lang('Client Management')</h3>
          <ul class="breadcrumb">
            <li>
              <a href="{{url('/admin/dashboard')}}">@lang('Dashboard')</a>
            </li>

            <li>@lang('Client Management')</li>
          </ul>
        </div>

        @if(session()->has('success'))
        <script>
          // Display toastr notification with the success message
          toastr.success('{{ session('
            success ') }}');
        </script>
        @endif
        @if(session()->has('error'))
        <script>
          // Display toastr notification with the success message
          toastr.success('{{ session('
            error ') }}');
        </script>
        @endif

        <div class="col-sm-12 mb-2">
          <style>
            .nav-btn1 {
              background-color: #00A2FE;
              color: white;
              border: 2px solid #00A2FE;
              width: fit-content;
            }

            .nav-btn1:hover {
              background-color: transparent;
              border: 2px solid #00A2FE;
              color: #00A2FE;
            }

            .nav-btn2 {
              border: 2px solid #00A2FE;
              color: #00A2FE;
            }

            .nav-btn2:hover {
              background-color: #00A2FE;
              color: white;
            }
          </style>

          <div class="d-flex flex-column-reverse flex-lg-row justify-content-center align-items-center py-3">
            <div class="d-flex justify-content-start flex-row mt-2">
            @if( $usr && $usr->can('add_client_manage'))
              <a class="btn px-3 py-2 me-3 nav-btn1" style="border-radius:5px;font-size:0.87rem;font-weight:bold" href="{{ url('/admin/add-client') }}">@lang('Add Client')</a>
            @endif
            </div>

            <!-- Search Bar -->
            <form action="" class="d-flex align-items-center justify-content-end flex-row w-100">
              <h5 style="font-size:18px">@lang('Filters'):</h5>
              <select name="filter" type="text" style="height:35px;width:fit-content;outline:none;margin-left:5px;margin-right:5px">
                <option value="all">@lang('All Clients')</option>
                <option value="first_name">@lang('First Name')</option>
                <option value="last_name">@lang('Last Name')</option>
                <option value="middle_name">@lang('Middle Name')</option>
                <option value="yes">@lang('Vip')</option>
                <option value="no">@lang('General')</option>
                <option value="email">@lang('Email')</option>
                <option value="phone">@lang('Phone No')</option>
              </select>
              <input type="text" name="search" style="height:35px;width:20%;outline:none" value="{{$search}}" />
              <button class="btn px-3 py-2" style="background-color:#D5924D;margin-left:10px;border-radius:5px;height:40px" href="{{ url('/admin/dashboard') }}"><i class="fas fa-search" style="color:white;font-size:14px"></i></button>
            </form>
            <button data-bs-toggle="modal" data-bs-target="#client-export" type="button" class="btn btn-success px-3 py-2" style="margin-left:10px;border-radius:5px;height:40px" href="{{ url('/admin/dashboard') }}"><i class="fas fa-print" style="color:white;font-size:18px"></i></button>
          </div>

          @if(count($clients) > 0)
          <div style="overflow-x:scroll">
            <table class="table bg--body">
              <thead>
                <tr>
                  <th>@lang('Client Name')</th>
                  <th>@lang('Type')</th>
                  <th>@lang('Email')</th>
                  <th>@lang('Phone')</th>
                  <th>@lang('Date')</th>
                  <th>@lang('Listings')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($clients as $client)
                <tr>
                  <td style="min-width:100px">{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}</td>
                  <td style="min-width:100px">
                    <div class="d-flex justify-content-center">
                      @if($client->is_vip === 'yes')
                      <div title="VIP" style="font-weight:bold;background-color:#800080;border-radius:100%;color:white;width:fit-content;padding:3px 8px 5px 8px"><i class="fas fa-crown" style="font-size:12px"></i></div>
                      @else
                      <div title="Normal" style="font-weight:bold;background-color:orange;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px"><i class="fas fa-user" style="font-size:14px"></i></div>
                      @endif
                    </div>
                  </td>
                  <td style="min-width:100px">
                    {{$client->email}}
                  </td>
                  <td style="min-width:130px">{{$client->phone}}</td>
                  <td style="min-width:100px">
                    {{date_format($client->created_at,"d M Y")}}
                  </td>
                  <td style="min-width:100px">
                    <div class="d-flex justify-content-center">
                    @if( $usr && $usr->can('view_client_manage'))
                      <a title="View Client" href="{{url('/admin/view-client')}}/{{$client->id}}" style="border:none;font-weight:bold;background-color:orange;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px"><i class="fas fa-eye" style="font-size:14px"></i></a>
                    @endif
                      <form id="client-form-{{$client->id}}" data-client-id="{{$client->id}}" onSubmit="DeleteClient(this)" action="{{url('/admin/delete-client')}}" method="post" class="mx-2"> <input type="hidden" name="id" value="{{$client->id}}" />
                      @if( $usr && $usr->can('delete_client_manage'))
                        <button title="Delete Client" style="border:none;font-weight:bold;background-color:red;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px">
                          <i id="form-btn-text-{{$client->id}}" class="fas fa-trash" style="font-size:14px"></i>
                          <div id="loader-{{$client->id}}" class="d-none">
                            <div class="spinner-border" style="width:14px;height:14px" role="status"><span class="sr-only">Loading...</span></div>
                          </div>
                      @endif
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
            </table>
          </div>
          {{ $clients->links() }}
          @else
          <div class="d-flex justify-content-center align-items-center w-100" style="height: calc(100vh - 400px)">
            <h4>@lang('No Client Found')!</h4>
          </div>
          @endif

        </div>

        <script>
          $(document).ready(function() {
            $('#print_options').on('change', function() {
              const option = $('#print_options').val();
              console.log(option)
              if (option === 'all-time') {
                $('#date_start').css('display', 'none')
                $('#date_end').css('display', 'none')
              } else {
                $('#date_start').css('display', 'flex')
                $('#date_end').css('display', 'flex')
              }
            });
          });
        </script>

        <script>
          $(document).ready(function() {
            $('#pdf-form').on('submit', function() {
              $('#loader-download').removeClass('d-none');
              $('#download-txt').addClass('d-none');
            });
          });
        </script>

        <!-- Invest Modal -->
        <div class="modal fade" id="client-export">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{url('/admin/export-clients-pdf')}}" method="POST" id="pdf-form" style="gap:10px" class="d-flex me-5 flex-column align-items-center justify-content-center my-2 mx-4">
                @csrf
                <div class="d-flex flex-column align-items-center" style="width:50%">
                  <h4 style="font-size:16px;align-self:start">@lang('Filter')</h4>
                  <select id="print_options" name="print-options" class="form-control" style="width:100%">
                    <option value="all-time">@lang('All Time')</option>
                    <option value="by-date">@lang('By Date')</option>
                  </select>
                </div>
                <div id="date_start" class="flex-column align-items-center" style="display:none;width:50%">
                  <h4 style="font-size:16px;align-self:start">@lang('Start Date')</h4>
                  <input type="date" name="date_start" class="form-control" style="width:100%" />
                </div>
                <div id="date_end" class="flex-column align-items-center" style="display:none;width:50%">
                  <h4 style="font-size:16px;align-self:start">@lang('End Date')</h4>
                  <input type="date" name="date_end" class="form-control" style="width:100%" />
                </div>
                <div class="d-flex me-5 w-100 justify-content-center">
                  <button class="btn px-3 py-2" style="color:white;background-color:#D5924D;margin-left:10px;border-radius:5px;height:40px">
                    <span id="download-txt">@lang('Download')</span>
                    <div id="loader-download" class="d-none">
                      <div class="spinner-border" style="width:14px;height:14px" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                  </button>
                </div>
              </form>
            </div>
          </div>
@else
<div class="container-fluid p-4">
    <p>You don't have permission to view this page</p>
</div>
@endif

@endsection