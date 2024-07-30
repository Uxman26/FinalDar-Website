@extends('layouts.app')
@section('content')
    @push('title')
        <title>@lang('Listing Details')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endpush
    @php
        $media = json_decode($listing->media) ?? [];
    @endphp

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbPIrXFERaxgSurR_7wxbI-UdLRLTc94w&libraries=places&callback=initMap">
    </script>
    </script>

    <script>
        $(document).ready(function() {
            // Initialize globalImagePaths on document ready to ensure it's correctly set up from the PHP variable
            globalImagePaths = <?php echo json_encode($media); ?>;
            if (!Array.isArray(globalImagePaths)) {
                globalImagePaths = [];
            }

            // Function to update the media display area
            function updateMediaDisplay() {
                const mediaContainer = $('#media-placeholder');
                mediaContainer.empty();
                globalImagePaths.forEach(media => {
                    const mediaElement = $(`
                <div class="item col-2" style="position:relative;width:fit-content">
                    <p onclick="DeleteMedia('${media.path}');" style="z-index:999;top:8px;right:5px;position:absolute;display:flex;justify-content:center;align-items:center;">
                        <i class="fas fa-window-close" style="cursor:pointer;background-color:white;color:red"></i>
                    </p>
                    <img style="width:100px;height:100px;border:1px solid gray;border-radius:5px" src="${media.path}" class="mx-2 my-2 px-2 py-2" />
                </div>
            `);
                    mediaContainer.append(mediaElement);
                });
            }

            $('#file-picker-select').on('change', function(event) {
                var file = event.target.files[0];
                var formData = new FormData();
                formData.append('file', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ url('/admin/upload-media') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.path) {
                            globalImagePaths.push({
                                type: file.type,
                                path: response.path
                            });
                            updateMediaDisplay();
                            toastr.success('File Uploaded Successfully!');
                        }
                    },
                    error: function() {
                        toastr.error('File Upload Failed!');
                    }
                });
            });

            window.DeleteMedia = function(path) {
                const data = new FormData();
                data.append('name', path);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ url('/admin/delete-media') }}",
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function() {
                        globalImagePaths = globalImagePaths.filter(item => item.path !== path);
                        updateMediaDisplay();
                        toastr.success('File Deleted Successfully!');
                    },
                    error: function() {
                        toastr.error('File Deletion Failed!');
                    }
                });
            };
        });
        window.UpdateListing = function() {
            const formData = new FormData(document.getElementById('listing-create-form'));
            formData.append('media', JSON.stringify(globalImagePaths));
            formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // Ensure CSRF token is appended

            console.log("Data being sent to the server:", Object.fromEntries(formData
        .entries())); // Debugging output to console

            $.ajax({
                url: "{{ url('/admin/update-listing') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#loader').removeClass('d-none');
                    $('#form-btn-text').addClass('d-none');
                },
                success: function(response) {
                    toastr.success('Listing Updated Successfully!');
                    window.location.href = "{{ url('/admin/manage-listings') }}";
                },
                error: function(xhr) {
                    $('#loader').addClass('d-none');
                    $('#form-btn-text').removeClass('d-none');
                    // Handle errors
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation error. Please check your input.';
                        if (errors) {
                            errorMessage = Object.values(errors).map(function(errorFields) {
                                return errorFields.join(', ');
                            }).join("; ");
                        }
                        toastr.error(errorMessage);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        }
        // function initMap() {
        //   var map = new google.maps.Map(document.getElementById('map'), {
        //     center: {
        //       lat: 19.9625593,
        //       lng: 56.2313026
        //     },
        //     zoom: 10
        //   });
        //   var marker = new google.maps.Marker({
        //     map: map,
        //     draggable: true,
        //     animation: google.maps.Animation.DROP,
        //     position: {
        //       lat: 19.9625593,
        //       lng: 56.2313026
        //     }
        //   });

        //   function getMarkerAddress(position) {
        //     // Create a Geocoder object
        //     var geocoder = new google.maps.Geocoder();

        //     // Use the geocode method from the Geocoder object
        //     geocoder.geocode({
        //       'location': position
        //     }, function(results, status) {
        //       if (status === 'OK') {
        //         if (results[0]) {
        //           console.log(results[0].formatted_address);
        //           // Optionally update a field in your form with the address
        //           $('#location').val(results[0].formatted_address);
        //         } else {
        //           console.log('No results found');
        //         }
        //       } else {
        //         console.log('Geocoder failed due to: ' + status);
        //       }
        //     });
        //   }
        //   marker.addListener('dragend', function() {
        //     getMarkerAddress(marker.getPosition());
        //   });
        // }

        function initMap() {
            let  response = JSON.parse($('#lat_lng').val());
            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 2,
                center: {
                    lat: 0,
                    lng: 0
                },
                mapTypeId: "terrain",
            });

            let flightPlanCoordinates = [{
                    lat: 37.772,
                    lng: -122.214
                },
                {
                    lat: 21.291,
                    lng: -157.821
                },
                {
                    lat: -18.142,
                    lng: 178.431
                },
                {
                    lat: -27.467,
                    lng: 153.027
                },
            ];


            if (response !== undefined) {
                flightPlanCoordinates = response;
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 18,
                    center: {
                        lat: response[0]['lat'],
                        lng: response[0]['lng']
                    },
                    mapTypeId: "terrain",
                });
            }
            const flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
            });

            flightPath.setMap(map);
        }

        function getMarkerAddress(latLng) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'location': latLng
            }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const address = results[0].formatted_address;
                    const location = {
                        lat: results[0].geometry.location.lat(),
                        lng: results[0].geometry.location.lng()
                    };

                    $('#location').val(address);
                    $('#lat_lng').val(JSON.stringify(location));
                    toastr.success('Location Found!');
                } else {
                    toastr.error('Location Not Found!');
                }
            });
        }
    </script>
    @php
        $usr = session()->get('user');
        if ($usr && is_array($usr)) {
            $usr = \App\Models\Admin::find($usr['id']);
        }
    @endphp
    @if ($usr && $usr->can('view_client_listing'))
        <div class="container-fluid p-4">
            <div class="card mb-4">
                <div class="card-header">@lang('Update Listing')</div>
                <div class="card-body">

                    @if (session()->has('success'))
                        <div>
                            <h5 class="text-success text-center mb-2">{{ session()->get('success') }}</h5>
                        </div>
                    @endif

                    <div>
                        <div class="profile--card">
                            <form id="listing-create-form" class="row gy-4">
                                @csrf
                                <div class="col-md-6">
                                    <label for="serial_no" class="form-label">@lang('Kroki No')</label>
                                    <input type="text" id="serial_no" name="serial_no" class="form-control"
                                        value="{{ $listing->serial_no }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="title" class="form-label">@lang('Listing Title')</label>
                                    <input type="text" id="title" name="title" class="form-control"
                                        value="{{ $listing->title }}">
                                </div>
                                <input type="hidden" name="id" value="{{ $listing->id }}">
                                <div class="col-md-3">
                                    <label for="size" class="form-label">@lang('Size')</label>
                                    <input type="text" id="size" name="size" class="form-control"
                                        value="{{ $listing->size }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="client" class="form-label">@lang('Seller Client')</label>
                                    <div class="d-flex align-items-center">
                                        @if ($listing->client)
                                            <!-- Display only, no modification allowed -->
                                            <input type="text" class="form-control" value="{{ $listing->client }}"
                                                disabled>
                                            <input type="hidden" name="client" value="{{ $listing->client }}">
                                        @else
                                            <!-- Dropdown to select client -->
                                            <select id="searched-client" name="client" class="form-control me-2"
                                                style="flex-grow: 1;">
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}"
                                                        {{ $listing->client_id == $client->id ? 'selected' : '' }}>
                                                        {{ $client->first_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" style="flex-shrink: 0; width: 33%;"
                                                data-bs-toggle="modal" data-bs-target="#client-modal"
                                                class="btn btn-primary">Find</button>
                                        @endif
                                    </div>
                                </div>




                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('Status')</label>
                                    @error('status')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <select id="status" name="status" class="form-control">
                                            @if ($listing->status === 'On Sell')
                                                <option value="On Sell">@lang('On Sell')</option>
                                                <option value="Sold">@lang('Sold')</option>
                                            @else
                                                <option value="Sold">@lang('Sold')</option>
                                                <option value="On Sell">@lang('On Sell')</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('Location')</label>
                                    @error('location')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group d-flex align-items-center">
                                        <input id="location" type="text" autocomplete="phone" name="location"
                                            class="form-control" value="{{ $listing->location }}" readonly>
                                        <input id="lat_lng" type="hidden" autocomplete="phone" name="lat_lng"
                                            class="form-control" value="{{ $listing->lat_lng }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('Property Type')</label>
                                    @error('type')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <select id="type" name="type" class="form-control">
                                            @if ($listing->type === 'Land')
                                                <option value="Land">@lang('Land')</option>
                                                <option value="Villa">@lang('Villa')</option>
                                                <option value="Appartment">@lang('Apartment')</option>
                                            @elseif($listing->type === 'Villa')
                                                <option value="Villa">@lang('Villa')</option>
                                                <option value="Appartment">@lang('Apartment')</option>
                                                <option value="Land">@lang('Land')</option>
                                            @else
                                                <option value="Appartment">@lang('Apartment')</option>
                                                <option value="Land">@lang('Land')</option>
                                                <option value="Villa">@lang('Villa')</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('Land Type')</label>
                                    @error('land_type')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <select id="land_type" name="land_type" class="form-control">
                                            <option value="Residential">Residential</option>
                                            <option @if ($listing->land_type == 'Commercial') selected @endif value="Commercial">
                                                Commercial</option>
                                            <option @if ($listing->land_type == 'Residential and Commercial') selected @endif
                                                value="Residential and Commercial">Residential and Commercial</option>
                                            <option @if ($listing->land_type == 'Agricultural') selected @endif value="Agricultural">
                                                Agricultural</option>
                                            <option @if ($listing->land_type == 'Industrial') selected @endif value="Industrial">
                                                Industrial</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- <div class="d-flex align-items-center justify-content-center" style="width:100%;height:400px;border:1px solid blue;" class="my-5" ><h4>Map Will Show Here..</h4></div>    -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="file" name="image" id="imageInput" accept="image/*">
                                    </div>
                                    <br>
                                    <div id="map" style="height:400px; width:100%"></div>
                                </div>



                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('Amount')</label>
                                    @error('amount')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <input id="amount" type="text" autocomplete="phone" name="amount"
                                            class="form-control" value="{{ $listing->amount }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('No of Bedrooms')</label>
                                    @error('no_bedrooms')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <input id="no_bedrooms" type="text" autocomplete="phone" name="no_bedrooms"
                                            class="form-control" value="{{ $listing->no_bedrooms }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('No of Toilets')</label>
                                    @error('no_toilets')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <input id="no_toilets" type="text" autocomplete="phone" name="no_toilets"
                                            class="form-control" value="{{ $listing->no_toilets }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('No of Majlis')</label>
                                    @error('no_majlis')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <input id="no_majlis" type="text" autocomplete="phone" name="no_majlis"
                                            class="form-control" value="{{ $listing->no_majlis }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('No of Floors')</label>
                                    @error('no_floors')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <input id="no_floors" type="text" autocomplete="phone" name="no_floors"
                                            class="form-control" value="{{ $listing->no_floors }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-xxl-4">
                                    <label for="phone" class="form-label">@lang('No of Kitchens')</label>
                                    @error('no_kitchens')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error"
                                            class="form-label text-sm ">{{ $message }}</label>
                                    @enderror
                                    <div class="input-group">
                                        <input id="no_kitchens" type="text" autocomplete="phone" name="no_kitchens"
                                            class="form-control" value="{{ $listing->no_kitchens }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end w-100 col-sm-12 my-4">
                                    <div class="text-end mx-2">
                                        <button onclick="UpdateListing()" type="button" class="btn btn-primary"><span
                                                id="form-btn-text" class="">Update</span>
                                            <div id="loader" class="d-none">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="text-end">
                                        <a title="print"
                                            href="{{ url('/admin/export-listing-pdf') }}/{{ $listing->id }}"
                                            class="btn btn-success"><span id="form-btn-text"><i class="fas fa-print"
                                                    style="font-size:1.2rem"></i></span></a>
                                    </div>
                                </div>

                            </form>


                        </div>
                        <div class="form-group">
                            <label for="file-picker-select">@lang('Upload Media')</label>
                            <input class="form-control" type="file" id="file-picker-select" name="file"
                                accept="video/*, image/*">
                        </div>


                        <form id="file-picker" enctype='multipart/form-data'>
                            <h4>@lang('Land Media')</h4>
                            @error('media')
                                <label style="color:red;font-size:0.7rem" for="fullname-error"
                                    class="form-label text-sm ">{{ $message }}</label>
                            @enderror
                            <div style="display:relative;border:1px solid #28628B;height:200px;border-radius:10px">
                                @csrf

                                <!-- <i class="fas fa-window-close" ><i/>  -->
                                <div id="media-placeholder" class="d-flex flex-wrap align-items-center"></div>

                                <!-- File Loader -->
                                <div id="file-loader"
                                    style="display:none;width:100px;height:100px;border:1px solid gray;border-radius:5px"
                                    class="align-items-center justify-content-center col-2 mx-2 my-2 px-2 py-2">
                                    <img src="/file-loader.gif" style="width:70px" />
                                </div>
                                <!-- File Loader -->

                            </div>
                    </div>
                </div>
                </form>

                <div class="d-flex justify-content-evenly w-100">
                    <div class="d-flex flex-column align-items-center col-sm-6 col-lg-2 col-xxl-2 mb-3">
                        <label for="phone" class="form-label" style="font-weight:bold">@lang('Created By')</label>
                        <div class="input-group">
                            <input type="text" readonly class="form-control" value="{{ $listing->created_by }}">
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-center col-sm-6 col-lg-2 col-xxl-2 mb-3">
                        <label for="phone" class="form-label" style="font-weight:bold">@lang('Created At')</label>
                        <div class="input-group">
                            <input type="text" readonly class="form-control"
                                value="{{ date_format($listing->created_at, 'd M Y') }}">
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-center col-sm-6 col-lg-2 col-xxl-2 mb-3">
                        <label for="phone" class="form-label" style="font-weight:bold">@lang('Updated By')</label>
                        <div class="input-group">
                            <input style="width:fit-content" type="text" readonly class="form-control"
                                value="{{ $listing->updated_by }}">
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-center col-sm-6 col-lg-2 col-xxl-2 mb-3">
                        <label for="phone" class="form-label" style="font-weight:bold">@lang('Updated At')</label>
                        <div class="input-group">
                            <input type="text" readonly class="form-control"
                                value="{{ date_format($listing->updated_at, 'd M Y') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Modal -->
        <div class="modal fade" id="client-modal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientModalLabel">@lang('Client Search')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="get-clients-form" class="mb-3">
                            @csrf
                            <div class="input-group mb-3">
                                <label for="client-search-filter" class="input-group-text">@lang('Filters'):</label>
                                <select class="form-select" id="client-search-filter" name="filter"
                                    aria-label="Filter selection">
                                    <option value="first_name">@lang('First Name')</option>
                                    <option value="last_name">@lang('Last Name')</option>
                                    <option value="middle_name">@lang('Middle Name')</option>
                                    <option value="email">@lang('Email')</option>
                                    <option value="phone">@lang('Phone No')</option>
                                </select>
                                <input type="text" class="form-control" name="search_term"
                                    placeholder="@lang('Search client')" aria-label="Search client">
                                <button class="btn btn-outline-primary" type="submit">@lang('Search')</button>
                            </div>
                        </form>
                        <div id="client-loader" class="d-flex justify-content-center my-2" style="display:none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="client-list" class="list-group"></div>
                        <div id="no_client" class="alert alert-info mt-2" style="display:none;">@lang('No Client Found')</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Invest Modal -->
        <div class="modal fade" id="client-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="get-clients-form" class="d-flex align-items-center justify-content-center my-2 mx-4">
                        @csrf
                        <h5 style="font-size:18px">@lang('Filters'):</h5>
                        <select class="form-control" name="filter" type="text"
                            style="height:35px;width:30%;outline:none;margin-left:5px;margin-right:5px">
                            <option value="first_name">@lang('First Name')</option>
                            <option value="last_name">@lang('Last Name')</option>
                            <option value="middle_name">@lang('Middle Name')</option>
                            <option value="email">@lang('Email')</option>
                            <option value="phone">@lang('Phone No')</option>
                        </select>
                        <input type="text" name="search" style="height:35px;width:35%;outline:none"
                            value="" />
                        <button class="btn px-3 py-2"
                            style="background-color:#D5924D;margin-left:10px;border-radius:5px;height:40px"
                            href="{{ url('/admin/dashboard') }}"><i class="fas fa-search"
                                style="color:white;font-size:14px"></i></button>
                    </form>
                    <div id="client-list"
                        style="border:1px solid #26826B;max-height:200px;border-radius:5px;overflow-y:scroll;overflow-x:hidden;scrollbar-color:yellow"
                        class="px-2 py-2 mx-2 my-2">

                    </div>

                </div>
            </div>
        </div>
        <script>
            $('#imageInput').change(function() {
                var formData = new FormData();
                var file = $(this)[0].files[0];
                formData.append('image', file);

                $.ajax({
                    url: "{{ url('/admin/extractText') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Text extracted:', response);
                        $('#lat_lng').val(JSON.stringify(response));
                        initMap();

                        // Handle the response as needed
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle errors
                    }
                });
            });
        </script>
    @else
        <div class="container-fluid p-4">
            <p>You don't have permission to view this page</p>
        </div>
    @endif

@endsection
