@extends('layouts.app')
@section('content')
@push('title')
<title>@lang('User Management')</title>
@endpush
@push('header-scripts')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('manage_user'))

<div class="layout-page">
  <!-- Navigation and other content -->

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="card">
        <div class="card-header">@lang('User Management')</div>
        <div class="card-body">
          <!-- Success and Error Messages -->
          @if(session()->has('success'))
          <script>
            toastr.success('{{ session('
              success ') }}');
          </script>
          @endif
          @if(session()->has('error'))
          <script>
            toastr.error('{{ session('
              error ') }}');
          </script>
          @endif

          <!-- User Management Table -->
          <div class="table-responsive text-nowrap">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>@lang('User Name')</th>
                  <th>@lang('Email')</th>
                  <th>@lang('Phone')</th>
                  <th>@lang('Date')</th>
                  <th>@lang('Status')</th>
                  <th>@lang('Actions')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                <tr>
                  <td>{{$user->user_name}}</td>
                  <td>{{$user->email}}</td>
                  <td>{{$user->phone}}</td>
                  <td>{{ date('M d, Y', strtotime($user->created_at)) }}</td>
                  <td>
                    @if($user->status === '1')
                    <span class="badge bg-label-success rounded-pill">@lang('Active')</span>
                    @else
                    <span class="badge bg-label-danger rounded-pill">@lang('Banned')</span>
                    @endif
                  </td>
                  <td>
                    <a href="javascript:void(0);" onclick="DeleteClient({{ $user->id }})" class="btn btn-icon btn-outline-danger"><i class="bx bx-trash"></i></a>
                    @if($user->status === '1')
                    <a href="javascript:void(0);" onclick="BanClient({{ $user->id }})" class="btn btn-icon btn-outline-warning"><i class="bx bx-block"></i></a>
                    @else
                    <a href="javascript:void(0);" onclick="UnBanClient({{ $user->id }})" class="btn btn-icon btn-outline-success"><i class="bx bx-refresh"></i></a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- unBan Form Function Start -->
<script>
  function UnBanClient(id) {
    // Show the loader
    $(`#loader-unban-${id}`).removeClass('d-none');
    $(`#form-unban-btn-text-${id}`).addClass('d-none');

    const formData = new FormData();
    formData.append('id', id);

    // Make an Ajax POST request
    $.ajax({
      type: 'POST',
      url: '{!! url("/admin/unban-user") !!}', // Replace with your actual endpoint
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        window.location.reload()
        toastr.success('User Successfully UnBanned!');
        // location.reload(true); // 'true' forces a reload from the server
        $(`#loader-unban-${id}`).addClass('d-none');
        $(`#form-unban-btn-text-${id}`).removeClass('d-none');
      },
      error: function(error) {
        toastr.error('Internal Server Error!');
        $(`#loader-ban-${id}`).addClass('d-none');
        $(`#form-unban-btn-text-${id}`).removeClass('d-none');
        // Handle the error response here
      }
    });

  }
</script>

<!-- unBan Form Function End -->
<!-- Ban Form Function Start -->
<script>
  function BanClient(id) {
    // Show the loader
    $(`#loader-ban-${id}`).removeClass('d-none');
    $(`#form-ban-btn-text-${id}`).addClass('d-none');

    const formData = new FormData();
    formData.append('id', id);

    // Make an Ajax POST request
    $.ajax({
      type: 'POST',
      url: '{!! url("/admin/ban-user") !!}', // Replace with your actual endpoint
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        window.location.reload()
        toastr.success('User Successfully Banned!');
        // location.reload(true); // 'true' forces a reload from the server
        $(`#loader-ban-${id}`).addClass('d-none');
        $(`#form-ban-btn-text-${id}`).removeClass('d-none');
      },
      error: function(error) {
        toastr.error('Internal Server Error!');
        $(`#loader-ban-${id}`).addClass('d-none');
        $(`#form-ban-btn-text-${id}`).removeClass('d-none');
        // Handle the error response here
      }
    });

  }
</script>

<!-- Ban Form Function End -->
<!-- Delete Form Function Start -->
<script>
  function DeleteClient(id) {
    // Show the loader
    $(`#loader-${id}`).removeClass('d-none');
    $(`#form-btn-text-${id}`).addClass('d-none');

    const formData = new FormData();
    formData.append('id', id);

    // Make an Ajax POST request
    $.ajax({
      type: 'POST',
      url: '{!! url("/admin/delete-user") !!}', // Replace with your actual endpoint
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log(response)
        toastr.success('User Successfully Deleted!');
        // location.reload(true); // 'true' forces a reload from the server
        $(`#loader-${id}`).addClass('d-none');
        $(`#form-btn-text-${id}`).removeClass('d-none');
      },
      error: function(error) {
        toastr.error('Internal Server Error!');
        $(`#loader-${id}`).addClass('d-none');
        $(`#form-btn-text-${id}`).removeClass('d-none');
        // Handle the error response here
      }
    });

  }
</script>

<!-- Delete Form Function End -->

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