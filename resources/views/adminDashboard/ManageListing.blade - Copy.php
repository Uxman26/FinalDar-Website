@extends('layouts.app')
@section('content')
@push('title')
<title>Land Management</title>
@endpush
@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp
@if( $usr && $usr->can('manage_listing'))
<div class="layout-page">
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row">
        <div class="col-md-3">
          <!-- Filter Sidebar -->
          <div class="card">
            <div class="card-header">
              <h5>@lang('Refined Search')</h5>
            </div>
            <div class="card-body">
              <form id="advancedSearch">
                <!-- Filter by Type -->
                <div class="mb-3">
                  <label class="form-label">@lang('Land Type')</label>
                  <select class="form-select" name="type">
                    <option value="">@lang('Villa')</option>
                    <option value="residential">@lang('Apartment')</option>
                    <option value="commercial">@lang('Land')</option>
                  </select>
                </div>
                <!-- Filter by Size -->
                <div class="mb-3">
                  <label class="form-label">@lang('Size Range (sqm)')</label>
                  <input type="range" class="form-range" name="size_range" min="0" max="10000" value="0" oninput="this.nextElementSibling.value = this.value">
                  <output>0</output> sqm
                </div>
                <!-- Additional Filters -->
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="with_pool" id="withPool">
                    <label class="form-check-label" for="withPool">
                      @lang('With Swimming Pool')
                    </label>
                  </div>
                </div>
                <!-- Actions -->
                <button type="submit" class="btn btn-primary">@lang('Apply Filters')</button>
                <button type="button" class="btn btn-outline-secondary" onclick="resetForm('#advancedSearch')">@lang('Clear Filters')</button>
              </form> <a class="btn btn-success" href="{{ url('/admin/add-listing') }}">@lang('Add Listing')</a>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <!-- Listing Table -->
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>@lang('Kroki No')</th>
                      <th>@lang('Title')</th>
                      <th>@lang('Land Type')</th>
                      <th>@lang('Land Size')</th>
                      <th>@lang('Date')</th>
                      <th>@lang('Actions')</th>
                    </tr>
                  </thead>
                  <tbody id="listingResults">
                  <tbody>
                    @foreach($listings as $listing)
                    <tr>
                      <td style="min-width:100px">
                        <div><span>{{$listing->serial_no}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div><span>{{$listing->title}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div><span>{{$listing->type}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div><span>{{$listing->size}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div><span>{{date_format($listing->created_at,'d M Y')}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div class="d-flex justify-content-center">
                          <a title="View Listing" href="{{url('/admin/viewlisting')}}/{{$listing->id}}" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:white; width:fit-content; padding:3px 8px 5px 8px">
                            <i class="fas fa-eye me-1" style="font-size:20px; color: #728294;"></i>
                          </a>
                          <a title="View Listing" href="{{url('/admin/view-listing')}}/{{$listing->id}}" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:white; width:fit-content; padding:3px 8px 5px 8px">
                            <i class="bx bx-edit-alt" style="font-size:20px; color:green;"></i>
                          </a>

                          <form id="listing-form-{{$listing->id}}" data-listing-id="{{$listing->id}}" onSubmit="DeleteListing(this)" action="{{url('/admin/delete-listing')}}" method="post"> @csrf
                            <input type="hidden" name="id" value="{{$listing->id}}" />
                            <button title="Delete Client" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:red; width:fit-content; padding:3px 9px 5px 9px">
                              <i id="form-btn-text-{{$listing->id}}" class="bx bx-trash" style="font-size:20px; color:red;"></i>
                            </button>

                            <div id="loader-{{$listing->id}}" class="d-none">
                              <div class="spinner-border" style="width:14px;height:14px" role="status"><span class="sr-only">Loading...</span></div>
                            </div>
                            </button>
                          </form>
                        </div>
                        <!-- <button title="Delete Listing" style="border:none;font-weight:bold;background-color:red;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px" ><i class="fas fa-trash" style="font-size:14px" ></i></button>	 -->
                      </td>
                    </tr>
                    @endforeach
                </table>
                {{ $listings->links() }}
              </div>

            </div>
            </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script>
    function resetForm(formSelector) {
      $(formSelector).trigger('reset');
      updateListings(); // Optionally, automatically update listings after reset.
    }

    $('#advancedSearch').on('submit', function(event) {
      event.preventDefault();
      updateListings();
    });

    function updateListings() {
      let formData = $('#advancedSearch').serialize();
      $.ajax({
        url: '{{ url("/admin/filter-listings") }}',
        type: 'GET',
        data: formData,
        success: function(data) {
          $('#listingResults').html(data);
        }
      });
    }
  </script>

  <script>
    function DeleteListing(id) {
      // Show the loader
      $(`#loader-${id}`).removeClass('d-none');
      $(`#form-btn-text-${id}`).addClass('d-none');

      const formData = $(`#listing-form-${id}`).serialize();
      //  $(`#listing-form-${id}`).e.preventDefault()
      // Make an Ajax POST request
      $.ajax({
        type: 'POST',
        url: '{!! url("/admin/delete-listing") !!}', // Replace with your actual endpoint
        data: formData,
        success: function(response) {
          toastr.success('Listing Successfully Deleted!');
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

    <!-- Filters & Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- Search and Filter -->
      <form action="" class="d-flex align-items-center">
        <h5 class="me-3">@lang('Filters'):</h5>

        <select name="filter" class="form-select me-2" aria-label="Filter selection">

          <!-- Your options here -->
          <option value="serial_no">@lang('Kroki No')</option>
          <option value="title">@lang('Title')</option>
          <option value="size">@lang('Size')</option>
          <option value="no_bedrooms">@lang('No Of Bedrooms')</option>
          <option value="no_toilets">@lang('No Of Toilets')</option>
          <option value="no_majlis">@lang('No Of Majlis')</option>
          <option value="no_floors">@lang('No Of Floors')</option>
          <option value="no_kitchens">@lang('No Of Kitchens')</option>

        </select>

        <input type="text" name="search" class="form-control me-2" placeholder="@lang('Search...')" value="{{$search}}" />
        <button class="btn btn-primary" type="submit"><i class="bx bx-search"></i></button>
      </form>

      <!-- Add Listing Button -->
      <a class="btn btn-success" href="{{ url('/admin/add-listing') }}">@lang('Add Listing')</a>
    </div>
    <!-- Listings Table -->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          @if(count($listings) > 0)
          <table class="table bg--body">
            <thead>
              <tr>
                <th>@lang('Kroki No')</th>
                <th>@lang('Title')</th>
                <th>@lang('Land Type')</th>
                <th>@lang('Land Size')</th>
                <th>@lang('Date')</th>
                <th>@lang('Actions')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($listings as $listing)
              <tr>
                <td style="min-width:100px">
                  <div><span>{{$listing->serial_no}}</span></div>
                </td>
                <td style="min-width:100px">
                  <div><span>{{$listing->title}}</span></div>
                </td>
                <td style="min-width:100px">
                  <div><span>{{$listing->type}}</span></div>
                </td>
                <td style="min-width:100px">
                  <div><span>{{$listing->size}}</span></div>
                </td>
                <td style="min-width:100px">
                  <div><span>{{date_format($listing->created_at,'d M Y')}}</span></div>
                </td>
                <td style="min-width:100px">
                  <div class="d-flex justify-content-center">
                  @if( $usr && $usr->can('new'))
                    <a title="View Listing" href="{{url('/admin/viewlisting')}}/{{$listing->id}}" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:white; width:fit-content; padding:3px 8px 5px 8px">
                      <i class="fas fa-eye me-1" style="font-size:20px; color: #728294;"></i>
                    </a>
                  @endif
                    <a title="View Listing" href="{{url('/admin/view-listing')}}/{{$listing->id}}" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:white; width:fit-content; padding:3px 8px 5px 8px">
                      <i class="bx bx-edit-alt" style="font-size:20px; color:green;"></i>
                    </a>

                    <form id="listing-form-{{$listing->id}}" data-listing-id="{{$listing->id}}" onSubmit="DeleteListing(this)" action="{{url('/admin/delete-listing')}}" method="post"> @csrf
                      <input type="hidden" name="id" value="{{$listing->id}}" />
                      <button title="Delete Client" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:red; width:fit-content; padding:3px 9px 5px 9px">
                        <i id="form-btn-text-{{$listing->id}}" class="bx bx-trash" style="font-size:20px; color:red;"></i>
                      </button>

                      <div id="loader-{{$listing->id}}" class="d-none">
                        <div class="spinner-border" style="width:14px;height:14px" role="status"><span class="sr-only">Loading...</span></div>
                      </div>
                      </button>
                    </form>
                  </div>
                  <!-- <button title="Delete Listing" style="border:none;font-weight:bold;background-color:red;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px" ><i class="fas fa-trash" style="font-size:14px" ></i></button>	 -->
                </td>
              </tr>
              @endforeach
          </table>
          {{ $listings->links() }}
        </div>
        @else
        <div class="d-flex justify-content-center align-items-center w-100" style="height: calc(100vh - 400px)">
          <h4>@lang('No Listing Found')!</h4>
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
            <form action="{{url('/admin/export-listings-pdf')}}" method="POST" id="pdf-form" style="gap:10px" class="d-flex me-5 flex-column align-items-center justify-content-center my-2 mx-4">
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
@endif
        @endsection