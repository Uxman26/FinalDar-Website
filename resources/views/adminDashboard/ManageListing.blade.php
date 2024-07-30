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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="layout-page">
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row">
        <div class="col-md-3">
        <div class="card">
  <div class="card-header">
    <h5>@lang('Search')</h5>
  </div>
  <div class="card-body">
    <form id="advancedSearch">
      <!-- Filter by Kroki No -->
      <div class="mb-3">
        <label class="form-label">@lang('Kroki No')</label>
        <input type="text" class="form-control" name="serial_no">
      </div>
      <!-- Filter by Listing Title -->
      <div class="mb-3">
        <label class="form-label">@lang('Listing Title')</label>
        <input type="text" class="form-control" name="title">
      </div>
      <!-- Filter by Size -->
<!-- Filter by Minimum Size -->
<div class="mb-3">
    <label class="form-label">@lang('Minimum Size')</label>
    <input type="text" class="form-control" name="min_size" placeholder="@lang('e.g., 100 sqm')">
</div>

<!-- Filter by Maximum Size -->
<div class="mb-3">
    <label class="form-label">@lang('Maximum Size')</label>
    <input type="text" class="form-control" name="max_size" placeholder="@lang('e.g., 500 sqm')">
</div>

  
      <!-- Filter by Status -->
      <div class="mb-3">
        <label class="form-label">@lang('Status')</label>
        <select class="form-select" name="status">
          <option value="">@lang('Select Status')</option>
          <option value="On Sell">@lang('On Sell')</option>
          <option value="Sold">@lang('Sold')</option>
        </select>
      </div>
      <!-- Filter by Amount -->
      <div class="mb-3">
    <label class="form-label">@lang('Minimum Amount')</label>
    <input type="text" class="form-control" name="min_amount">
</div>
<div class="mb-3">
    <label class="form-label">@lang('Maximum Amount')</label>
    <input type="text" class="form-control" name="max_amount">
</div>

<!-- Filter by Property Type -->
<div class="mb-3">
    <label class="form-label">@lang('Property Type')</label>
    <select class="form-select" id="propertyType" name="type">
        <option value="Land">@lang('Land')</option>
        <option value="Villa">@lang('Villa')</option>
        <option value="Apartment">@lang('Apartment')</option>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">@lang('Land Type')</label>
    <select class="form-select" id="land_type" name="land_type">
      <option value="Residential">Residential</option>
            <option value="Commercial">Commercial</option>
            <option value="Residential and Commercial">Residential and Commercial</option>
            <option value="Agricultural">Agricultural</option>
            <option value="Industrial">Industrial</option>
    </select>
</div>

<!-- Dynamic Fields Section -->
<div id="dynamicFields" style="display: none;">
    <!-- Filter by Number of Bedrooms -->
    <div class="mb-3">
        <label class="form-label">@lang('No of Bedrooms')</label>
        <input type="text" class="form-control" name="no_bedrooms">
    </div>
    <!-- Filter by Number of Toilets -->
    <div class="mb-3">
        <label class="form-label">@lang('No of Toilets')</label>
        <input type="text" class="form-control" name="no_toilets">
    </div>
    <!-- Filter by Number of Majlis -->
    <div class="mb-3">
        <label class="form-label">@lang('No of Majlis')</label>
        <input type="text" class="form-control" name="no_majlis">
    </div>
    <!-- Filter by Number of Floors -->
    <div class="mb-3">
        <label class="form-label">@lang('No of Floors')</label>
        <input type="text" class="form-control" name="no_floors">
    </div>
    <!-- Filter by Number of Kitchens -->
    <div class="mb-3">
        <label class="form-label">@lang('No of Kitchens')</label>
        <input type="text" class="form-control" name="no_kitchens">
    </div>
</div>

      <!-- Actions -->
      <button type="submit" class="btn btn-primary">@lang('Apply Filters')</button>

    <a class="btn btn-success" href="{{ url('/admin/add-listing') }}">@lang('Add Listing')</a>
    </form>
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
                      <th>@lang('Property Type')</th>
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
                        <div><span>{{$listing->land_type}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div><span>{{$listing->size}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div><span>{{date_format($listing->created_at,'d M Y')}}</span></div>
                      </td>
                      <td style="min-width:100px">
                        <div class="d-flex justify-content-center">
                        @if( $usr && $usr->can('view_listing'))
                          <a title="View Listing" href="{{url('/admin/viewlisting')}}/{{$listing->id}}" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:white; width:fit-content; padding:3px 8px 5px 8px">
                            <i class="fas fa-eye me-1" style="font-size:20px; color: #728294;"></i>
                          </a>
                          @endif
                          @if( $usr && $usr->can('edit_listing'))
                          <a title="View Listing" href="{{url('/admin/view-listing')}}/{{$listing->id}}" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:white; width:fit-content; padding:3px 8px 5px 8px">
                            <i class="bx bx-edit-alt" style="font-size:20px; color:green;"></i>
                          </a>
                          @endif

                          <form id="listing-form-{{$listing->id}}" data-listing-id="{{$listing->id}}" onSubmit="DeleteListing(this)" action="{{url('/admin/delete-listing')}}" method="post"> @csrf
                            <input type="hidden" name="id" value="{{$listing->id}}" />
                            @if( $usr && $usr->can('delete_listing'))
                            <button title="Delete Client" style="border:none; font-weight:bold; background-color:transparent; border-radius:100%; color:red; width:fit-content; padding:3px 9px 5px 9px">
                              <i id="form-btn-text-{{$listing->id}}" class="bx bx-trash" style="font-size:20px; color:red;"></i>
                            </button>
                            @endif

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
      <div class="col-md-70">
        <!-- Listing Table -->
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>

                </thead>
                <tbody id="listingResults">
                  <!-- AJAX results will be injected here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>


  <script>
    $(document).ready(function() {
        $('#propertyType').on('change', function() {
            var selectedType = $(this).val();
            if (selectedType === 'Villa' || selectedType === 'Apartment') {
                $('#dynamicFields').show(); // Show the fields
            } else {
                $('#dynamicFields').hide(); // Hide the fields
            }
        });
        $('#land_type').on('change', function() {
            var selectedType = $(this).val();
            if (selectedType === 'Villa' || selectedType === 'Apartment') {
                $('#dynamicFields').show(); // Show the fields
            } else {
                $('#dynamicFields').hide(); // Hide the fields
            }
        });
    });
</script>

  <script>
    function resetForm(formSelector) {
      $(formSelector).trigger('reset');
      updateListings(); // Optionally, automatically update listings after reset.
    }
    $('#advancedSearch').on('submit', function(event) {
      event.preventDefault();
      let formData = $(this).serialize(); // Make sure all form fields are serialized

      $.ajax({
        url: '{{ route("filter-listings") }}', // Laravel blade syntax for generating URL
        type: 'GET',
        data: formData,
        success: function(data) {
          $('#listingResults').html(data); // Update the listings table with the new HTML
        },
        error: function(xhr, status, error) {
          console.error("Error occurred: " + error);
        }
      });
    });
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
@else
<div class="layout-page">
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
<p>You don't have permission to view this page</p>
</div>
      </div>
    </div>

@endif
@endsection