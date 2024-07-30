@extends('layouts.app')
@section('content')
@push('title')
<title>@lang('Appointment Scheduling')</title>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

@endpush
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('manage_appointment'))
<div class="dashborad--content">
  <div class="breadcrumb-area">
    <h3 class="title">@lang('Appointment Scheduling')</h3>
    <ul class="breadcrumb">
      <li>
        <a href="{{url('/admin/dashboard')}}">@lang('Dashboard')</a>
      </li>

      <li>@lang('Appointment Scheduling')</li>
    </ul>
  </div>
  @if(session()->has('success'))
  <div>
    <h5 class="text-success text-center mb-2">{{session()->get('success')}}</h5>
  </div>
  @endif
  @if(session()->has('error'))
  <div>
    <h5 class="text-danger text-center mb-2">{{session()->get('error')}}</h5>
  </div>
  @endif
  <!-- Calander Start -->
  <div id="calendar"></div>

</div>

<style>
  .fc-col-header-cell-cushion {
    text-decoration: none
  }

  .fc-view-harness {
    background-color: white
  }
</style>

<script>
  function CreateClient() {
    const form = new FormData()
    form.append('start_date', $('#start_date').val())
    form.append('end_date', $('#end_date').val())
    form.append('title', $('#title').val())
    form.append('description', $('#description').val())
    $('#event-text').css('display', 'none')
    $('#event-loader').css('display', 'flex')
    $.ajax({
      url: "{{url('/admin/create-event')}}", // Replace with your server-side endpoint
      type: 'POST',
      data: form,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(res) {
        $('#event-text').css('display', 'flex')
        $('#event-loader').css('display', 'none')
        toastr.success('Appointment Created Successfully!')
      },
      error: function(error) {
        // console.log(error.responseJSON)
        if (error.status === 500) {
          toastr.error('Internal Server Error')
        }
        if (error.status === 422) {
          const obj = error.responseJSON.errors;
          for (let prop in obj) {
            toastr.error(obj[prop][0])
          }
        }
        $('#event-text').css('display', 'flex')
        $('#event-loader').css('display', 'none')
      }
    })

  }
</script>

<script>
  function UpdateEvent() {
    const form = new FormData()
    form.append('id', $('#id').val())
    form.append('start_date', $('#ustart_date').val())
    form.append('end_date', $('#uend_date').val())
    form.append('title', $('#utitle').val())
    form.append('description', $('#udescription').val())
    $('#event-update-text').css('display', 'none')
    $('#event-update-loader').css('display', 'flex')
    $.ajax({
      url: "{{url('/admin/update-event')}}", // Replace with your server-side endpoint
      type: 'POST',
      data: form,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(res) {
        toastr.success('Appointment Updated Successfully!')
        $('#event-update-text').css('display', 'flex')
        $('#event-update-loader').css('display', 'none')
        window.location.reload()
      },
      error: function(error) {
        // console.log(error.responseJSON)
        if (error.status === 500) {
          toastr.error('Internal Server Error')
        }
        if (error.status === 422) {
          const obj = error.responseJSON.errors;
          for (let prop in obj) {
            toastr.error(obj[prop][0])
          }
        }
        $('#event-update-text').css('display', 'flex')
        $('#event-update-loader').css('display', 'none')
      }
    })

  }
</script>

<script>
  function DeleteEvent() {
    const form = new FormData()
    form.append('id', $('#id').val())
    $('#event-delete-text').css('display', 'none')
    $('#event-delete-loader').css('display', 'flex')
    $.ajax({
      url: "{{url('/admin/delete-event')}}", // Replace with your server-side endpoint
      type: 'POST',
      data: form,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(res) {
        toastr.success('Appointment Deleted Successfully!')
        $('#event-delete-text').css('display', 'flex')
        $('#event-delete-loader').css('display', 'none')
        window.location.reload()
      },
      error: function(error) {
        // console.log(error.responseJSON)
        if (error.status === 500) {
          toastr.error('Internal Server Error')
        }
        if (error.status === 422) {
          const obj = error.responseJSON.errors;
          for (let prop in obj) {
            toastr.error(obj[prop][0])
          }
        }
        $('#event-delete-text').css('display', 'flex')
        $('#event-delete-loader').css('display', 'none')
      }
    })

  }
</script>
<button id="modal-view-trigger" type="button" style="display:none;height:50px;width:33%;font-size:12px;outline:none;border-radius:2px" data-bs-toggle="modal" data-bs-target="#event-view-modal"></button>
<!-- Invest Modal -->
<div class="modal fade" id="event-view-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="event-form2" class="d-flex flex-column justify-content-center align-items-center my-3">
        @csrf
        <div class="d-flex align-items-center justify-content-evenly w-100 my-2 mx-4">
          <div>
            <h4 style="font-size:16px">@lang('Start Date')</h4>
            <input type="date" id="ustart_date" name="start_date" style="height:35px;width:100%;outline:none" value="" />
          </div>
          <div class="my-2">
            <h4 style="font-size:16px">@lang('End Date')</h4>
            <input type="date" id="uend_date" name="end_date" style="height:35px;width:100%;outline:none" value="" />
          </div>
        </div>
        <div class="w-100 px-4 my-2">
          <h4 style="font-size:16px">@lang('Title')</h4>
          <input type="text" id="utitle" name="title" style="height:35px;width:100%;outline:none" value="" />
        </div>
        <div class="w-100 px-4 my-2">
          <h4 style="font-size:16px">@lang('Description')</h4>
          <textarea type="text" id="udescription" name="description" style="height:80px;width:100%;outline:none" value=""></textarea>
        </div>
        <div class="d-flex justify-content-evenly w-100">
          <button onclick="UpdateEvent()" type="button" class="btn px-3 py-2 my-2" style="background-color:#D5924D;border-radius:5px;height:40px;color:white;style:font-size:12px !important">
            <p id="event-update-text">@lang('Update')</p>
            <div id="event-update-loader" style="display:none;justify-content:center;align-items:center;height:100%">
              <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
            </div>
          </button>
          <button onclick="DeleteEvent()" type="button" class="btn btn-danger px-3 py-2 my-2" style="border-radius:5px;height:40px;color:white;style:font-size:12px !important">
            <p id="event-delete-text">@lang('Delete')</p>
            <div id="event-delete-loader" style="display:none;justify-content:center;align-items:center;height:100%">
              <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
            </div>
          </button>

        </div>
      </form>
    </div>
  </div>
</div>
<button id="modal-trigger" type="button" style="display:none;height:50px;width:33%;font-size:12px;outline:none;border-radius:2px" data-bs-toggle="modal" data-bs-target="#event-modal"></button>
<!-- Invest Modal -->
<div class="modal fade" id="event-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="event-form" class="d-flex flex-column justify-content-center align-items-center my-3">
        @csrf
        <div class="d-flex align-items-center justify-content-evenly w-100 my-2 mx-4">
          <div>
            <h4 style="font-size:16px">@lang('Start Date')</h4>
            <input type="hidden" id="id" name="id" style="height:35px;width:100%;outline:none" value="" />
            <input type="text" id="start_date" name="start_date" style="height:35px;width:100%;outline:none" value="" />
          </div>
          <div class="my-2">
            <h4 style="font-size:16px">@lang('End Date')</h4>
            <input type="text" id="end_date" name="end_date" style="height:35px;width:100%;outline:none" value="" />
          </div>
        </div>
        <div class="w-100 px-4 my-2">
          <h4 style="font-size:16px">@lang('Title')</h4>
          <input type="text" id="title" name="title" style="height:35px;width:100%;outline:none" value="" />
        </div>
        <div class="w-100 px-4 my-2">
          <h4 style="font-size:16px">@lang('Description')</h4>
          <textarea type="text" id="description" name="description" style="height:80px;width:100%;outline:none" value=""></textarea>
        </div>
        <button onclick="CreateClient()" type="button" class="btn px-3 py-2 my-2" style="background-color:#D5924D;border-radius:5px;height:40px;color:white;style:font-size:12px !important">
          <p id="event-text">@lang('Add Event')</p>
          <div id="event-loader" style="display:none;justify-content:center;align-items:center;height:100%">
            <div class="spinner-border bg-dark" role="status"><span class="sr-only">Loading...</span></div>
          </div>
        </button>
      </form>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<!-- Calander End -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      selectable: true,
      select: function(date) {
        $('#modal-trigger').click();
        $('#start_date').val(date.startStr);
        $('#end_date').val(date.endStr);
      },
      eventClick: function(event) {
        console.log(event)
        $('#modal-view-trigger').click();
        $('#id').val(event.event.id);
        $('#ustart_date').val(moment(event.event.startStr).format('YYYY-MM-DD'));
        $('#uend_date').val(moment(event.event.endStr).format('YYYY-MM-DD'));
        $('#utitle').val(event.event.title);
        $('#udescription').val(event.event.extendedProps.description);
      },
      events: [
        @foreach($events as $event) {
          id: '{{$event['
          id ']}}',
          title: '{{ $event['
          title '] }}',
          description: '{{ $event['
          description '] }}',
          start: '{{ $event['
          start_date '] }}',
          end: '{{ $event['
          end_date '] }}',
        },
        @endforeach
      ],
    });
    calendar.render();
  });
</script>
@else
<div class="container-fluid p-4">
    <p>You don't have permission to view this page</p>
</div>
@endif
@endsection