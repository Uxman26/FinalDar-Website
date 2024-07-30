@extends('layouts.app')

@section('content')
    @php
        $usr = session()->get('user');
        if ($usr && is_array($usr)) {
            $usr = \App\Models\Admin::find($usr['id']);
        }
    @endphp
    @if ($usr && $usr->can('add_listing'))
        @push('title')
            <title>@lang('Add Listing')</title>
            <meta name="csrf-token" content="{{ csrf_token() }}" />
        @endpush
        <!-- Include your JavaScript scripts here -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#type').change(function() {
                    var selectedType = $(this).val();
                    switch (selectedType) {
                        case 'Land':
                            $('#locationContainer, #amountContainer').show();
                            $('#bedroomsContainer, #majlisContainer, #floorsContainer, #kitchensContainer')
                                .hide();
                            break;
                        case 'Villa':
                            $('#locationContainer, #amountContainer, #bedroomsContainer, #majlisContainer, #floorsContainer, #kitchensContainer')
                                .show();
                            break;
                        case 'Apartment':
                            $('#locationContainer, #amountContainer, #bedroomsContainer, #majlisContainer, #kitchensContainer')
                                .show();
                            $('#floorsContainer').hide();
                            break;
                    }
                }).trigger('change'); // Trigger the change function on page load to set initial visibility
            });
        </script>
        <script>
            let globalImagePaths = [];
        </script>
        <script>
            function addOptionToSelect(id, name) {
                // Close Bootstrap modal
                $('#searched-client').html('');

                // Add option to select
                let newOption = $('<option>', {
                    value: id,
                    text: name
                });
                let guestOption = $('<option>', {
                    value: 'Guest',
                    text: 'Guest'
                });
                $('#searched-client').append(newOption);
                $('#searched-client').append(guestOption);
                // $('#client-modal').removeClass('show');
                $('#client-modal').removeClass('show').css('display', 'none');
                $('.modal-backdrop').modal('hide').remove();
            }
        </script>
        <script>
            $(document).ready(function() {
                // Handler for form submission
                $('#get-clients-form').submit(function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();

                    // AJAX request to get clients
                    $.ajax({
                        url: '{{ url('/admin/get-clients') }}',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#client-loader').show();
                            $('#no_client').hide();
                            $('#client-list').html('');
                        },
                        success: function(response) {
                            $('#client-loader').hide();
                            if (response && response.users.length > 0) {
                                response.users.forEach(function(user) {
                                    const fullName =
                                        `${user.first_name} ${user.middle_name} ${user.last_name}`;
                                    let newUserElement = $(
                                        '<div class="searched-item list-group-item list-group-item-action" data-id="' +
                                        user.id + '" data-name="' + fullName + '">' +
                                        fullName + '</div>');
                                    $('#client-list').append(newUserElement);
                                });
                            } else {
                                $('#no_client').show().text('@lang('No Client Found')');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $('#client-loader').hide();
                            $('#client-list').html(
                                '<div class="alert alert-danger">@lang('An error occurred while fetching clients.')</div>');
                        }
                    });
                });

                // Handling click on dynamically generated list items
                $(document).on('click', '.searched-item', function() {
                    const clientId = $(this).data('id');
                    const clientName = $(this).data('name');

                    // Check if Guest option exists and remove it
                    $('#searched-client option[value="guest"]').remove();

                    // Add the selected client to the dropdown and select it
                    $('#searched-client').append(new Option(clientName, clientId, false, true)).trigger(
                        'change');

                    // Close the modal
                    $('#client-modal').modal('hide');
                });
            });
        </script>




        <script>
            $(document).ready(function() {
                // Handler for file selection
                $('#file-picker-select').on('change', function() {
                    const file = this.files[0];
                    if (file.type.startsWith('image') || file.type.startsWith('video')) {
                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('_token', '{{ csrf_token() }}');
                        $.ajax({
                            url: '{{ url('/admin/upload-media') }}',
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
                    } else {
                        toastr.error('Invalid File Type!');
                    }
                });

                // Update Media Display
                function updateMediaDisplay() {
                    const mediaContainer = $('#media-placeholder');
                    mediaContainer.empty();
                    globalImagePaths.forEach(function(media) {
                        const mediaElement = $(
                            '<div class="item col-2" style="position:relative;width:fit-content">' +
                            '<p onclick="DeleteMedia(\'' + media.path +
                            '\');" style="z-index:999;top:8px;right:5px;position:absolute;display:flex;justify-content:center;align-items:center;">' +
                            '<i class="fas fa-window-close" style="cursor:pointer;background-color:white;color:red"></i>' +
                            '</p>' +
                            '<img style="width:100px;height:100px;border:1px solid gray;border-radius:5px" src="' +
                            media.path + '" class="mx-2 my-2 px-2 py-2" />' +
                            '</div>');
                        mediaContainer.append(mediaElement);
                    });
                }

                // Delete Media
                window.DeleteMedia = function(path) {
                    const data = new FormData();
                    data.append('name', path);
                    data.append('_token', '{{ csrf_token() }}');
                    $.ajax({
                        url: '{{ url('/admin/delete-media') }}',
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
        </script>
        <script>
            function selectMedia() {
                $('#file-picker-select').click()
            }
        </script>

        <script>
            function DeleteMedia(fileName) {
                //  console.log(fileName)
                const data = new FormData()
                data.append('name', fileName);
                //  data.append('_csrf',file);
                data.append('csrf_token', $('input[name=_token]').val());
                $.ajax({
                    url: "{{ url('/admin/delete-media') }}", // Replace with your server-side endpoint
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {

                        const copy = globalImagePaths.filter(item => item.path !== fileName)
                        globalImagePaths = copy;
                        //  console.log(copy)
                        const domain = "{{ url('/') }}"

                        $('#media-placeholder .item').remove();
                        copy.forEach(function(media) {
                            if (media.type.startsWith('video')) {
                                const newUserElement = $(
                                    `<div class="item col-2" style="position:relative;width:fit-content" >` +
                                    `<p onclick="DeleteMedia('${media.path}');" style="z-index:999;top:8px;right:5px;position:absolute;display:flex;justifty-content:center;align-items:center;" ><i class="fas fa-window-close" style="cursor:pointer;background-color:white;color:red" ></i></p>` +
                                    `<image style="width:100px;height:100px;border:1px solid gray;border-radius:5px" src="${domain}/uploads/${media.path}" class="mx-2 my-2 px-2 py-2" />` +
                                    `</div>`
                                );
                                $('#media-placeholder').append(newUserElement);
                            }
                            if (media.type.startsWith('image')) {
                                const newUserElement = $(
                                    `<div class="item col-2" style="position:relative;width:fit-content" >` +
                                    `<p onclick="DeleteMedia('${media.path}');" style="z-index:999;top:8px;right:5px;position:absolute;display:flex;justifty-content:center;align-items:center;" ><i class="fas fa-window-close" style="cursor:pointer;background-color:white;color:red" ></i></p>` +
                                    `<image style="width:100px;height:100px;border:1px solid gray;border-radius:5px" src="${domain}/uploads/${media.path}" class="mx-2 my-2 px-2 py-2" />` +
                                    `</div>`
                                );
                                $('#media-placeholder').append(newUserElement);
                            }
                        });
                        toastr.success('File Deleted Successfully!');
                    },
                    error: function(error) {
                        toastr.error('File Deleted Failed!');
                    }
                })
            }
        </script>

        <script>
            function CreateListing() {
                const form = new FormData()
                form.append('serial_no', $('#serial_no').val())
                form.append('title', $('#title').val())
                form.append('size', $('#size').val())
                form.append('client', $('#searched-client').val())
                form.append('status', $('#status').val())
                form.append('location', $('#location').val())
                form.append('lat_lng', $('#lat_lng').val())
                form.append('type', $('#type').val())
                form.append('land_type', $('#land_type').val())
                form.append('amount', $('#amount').val())
                form.append('no_bedrooms', $('#no_bedrooms').val())
                form.append('no_floors', $('#no_floors').val())
                form.append('no_kitchens', $('#no_kitchens').val())
                form.append('no_toilets', $('#no_toilets').val())
                form.append('no_majlis', $('#no_majlis').val())
                form.append('media', JSON.stringify(globalImagePaths))
                $('#loader').removeClass('d-none');
                $('#form-btn-text').addClass('d-none');
                $.ajax({
                    url: "{{ url('/admin/create-listing') }}", // Replace with your server-side endpoint
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        toastr.success('Listing Creating Successfully!')
                        $('#loader').addClass('d-none');
                        $('#form-btn-text').removeClass('d-none');
                        window.location.href = "{{ url('/') }}/admin/manage-listings"
                    },
                    error: function(error) {
                        // console.log(error) 
                        $('#loader').addClass('d-none');
                        $('#form-btn-text').removeClass('d-none');
                        toastr.error('Internal Server Error!')
                    }
                })

            }
        </script>


        <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbPIrXFERaxgSurR_7wxbI-UdLRLTc94w&libraries=places&callback=initMap">
        </script>
        <script>
            // function initMap(longitude, latitude) {


            //     if (longitude !== undefined && latitude !== undefined) {
            //       var map = new google.maps.Map(document.getElementById('map'), {
            //         center: {
            //             lat: latitude,
            //             lng: longitude
            //         },
            //         zoom: 10
            //     });
            //         var marker = new google.maps.Marker({
            //             map: map,
            //             draggable: true,
            //             animation: google.maps.Animation.DROP,
            //             position: {
            //                 lat: latitude,
            //                 lng: longitude
            //             }
            //         });
            //     } else {
            //       var map = new google.maps.Map(document.getElementById('map'), {
            //         center: {
            //             lat: 19.9625593,
            //             lng: 56.2313026
            //         },
            //         zoom: 10
            //     });
            //         var marker = new google.maps.Marker({
            //             map: map,
            //             draggable: true,
            //             animation: google.maps.Animation.DROP,
            //             position: {
            //                 lat: 19.9625593,
            //                 lng: 56.2313026
            //             }
            //         });
            //     }

            //     // Add event listener to the marker to get the address when it is dropped
            //     marker.addListener('dragend', function() {
            //         getMarkerAddress(marker.getPosition());
            //     });
            // }
            function initMap(response) {
                
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
                } else {
                         map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: 19.9625593,
                        lng: 56.2313026
                    },
                    zoom: 10
                });
                     marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        position: {
                            lat: 19.9625593,
                            lng: 56.2313026
                        }
                    });
                }

                // Add event listener to the marker to get the address when it is dropped
                marker.addListener('dragend', function() {
                    getMarkerAddress(marker.getPosition());
                });
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
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'location': latLng
                }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            var address = results[0].formatted_address;
                            // Handle the address as needed (e.g., display in a form field)
                            const location = {
                                lat: results[0].geometry.location.lat(),
                                lng: results[0].geometry.location.lng()
                            }
                            $('#location').val(address)
                            $('#lat_lng').val(JSON.stringify(location))
                        }
                    } else {
                        console.error('Geocoder failed due to: ' + status);
                    }
                });
            }

            // Initialize the map directly as the callback
            // initMap();
            // window.addEventListener('load', initMap);
        </script>

        <div class="container-fluid p-4">
            <div class="card mb-4">
                <div class="card-header">@lang('Add Listing')</div>
                <div class="card-body">
                    <form id="listing-create-form" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="serial_no" class="form-label">@lang('Kroki No')</label>
                            <input type="text" id="serial_no" name="serial_no" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">@lang('Listing Title')</label>
                            <input type="text" id="title" name="title" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="size" class="form-label">@lang('Size')</label>
                            <input type="text" id="size" name="size" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="client" class="form-label">@lang('Seller Client')</label>
                            <div class="d-flex align-items-center">
                                <select id="searched-client" name="client" class="form-control me-2" style="flex-grow: 1;">
                                    <option value="guest">@lang('Guest')</option>
                                </select>
                                <button type="button" style="flex-shrink: 0; width: 33%;" data-bs-toggle="modal"
                                    data-bs-target="#client-modal" class="btn btn-primary">Find</button>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label for="status" class="form-label">@lang('Status')</label>
                            <select id="status" name="status" class="form-select">
                                <option value="On Sell">@lang('On Sell')</option>
                                <option value="Sold">@lang('Sold')</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="land_type" class="form-label">@lang('Land Type')</label>
                            <select id="land_type" name="land_type" class="form-select">
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Residential and Commercial">Residential and Commercial</option>
                                <option value="Agricultural">Agricultural</option>
                                <option value="Industrial">Industrial</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">@lang('Property Type')</label>
                            <select id="type" name="type" class="form-select">
                                <option value="Land">@lang('Land')</option>
                                <option value="Villa">@lang('Villa')</option>
                                <option value="Apartment">@lang('Apartment')</option>
                            </select>
                        </div>

                        <div id="amountContainer" class="col-md-6">
                            <label for="amount" class="form-label">@lang('Amount')</label>
                            <input type="number" id="amount" name="amount" class="form-control" value="0">
                        </div>

                        <div id="locationContainer" class="col-sm-6 col-lg-3 col-xxl-4">
                            <label for="location" class="form-label">@lang('Location')</label>
                            <input id="location" type="text" name="location" class="form-control" value="0">
                            <input id="lat_lng" type="hidden" name="lat_lng">
                            {{-- <div id="map" style="height:400px;"></div> --}}
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="file" name="image" id="imageInput" accept="image/*">
                            </div>
                            <br>
                            <div id="map" style="height:400px;"></div>
                        </div>
                        <div id="bedroomsContainer" class="col-md-6">
                            <label for="no_bedrooms" class="form-label">@lang('No of Bedrooms')</label>
                            <input type="number" id="no_bedrooms" name="no_bedrooms" class="form-control"
                                value="0">
                        </div>
                        <div id="majlisContainer" class="col-md-6">
                            <label for="no_majlis" the "form-label">@lang('No of Majlis')</label>
                            <input type="number" id="no_majlis" name="no_majlis" class="form-control" value="0">
                        </div>
                        <div id="floorsContainer" the "col-md-6">
                            <label for="no_floors" the "form-label">@lang('No of Floors')</label>
                            <input type="number" id="no_floors" name="no_floors" class="form-control" value="0">
                        </div>
                        <div id="kitchensContainer" class="col-md-6">
                            <label for="no_kitchens" the "form-label">@lang('No of Kitchens')</label>
                            <input type="number" id="no_kitchens" name="no_kitchens" class="form-control"
                                value="0">
                        </div>
                        <div class="col-12 text-end">
                            <button onclick="CreateListing()" type="button"
                                class="btn btn-primary">@lang('Create Listing')</button>
                        </div>
                    </form>
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
        </div>
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
                        style="border:1px solid #26826B;height:200px;border-radius:5px;overflow-y:scroll;overflow-x:hidden;scrollbar-color:yellow"
                        class="px-2 py-2 mx-2 my-2">
                        <div id="client-loader"
                            style="display:none;justify-content:center;align-items:center;height:100%">
                            <div class="spinner-border bg-dark" role="status"><span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div id="no_client" style="display:flex;justify-content:center;align-items:center;height:100%">
                            <p>@lang('No Client Found')!</p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @else
        <div class="container-fluid p-4">
            <p>You don't have permission to view this page</p>
        </div>
    @endif
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
                    initMap(response);
                    
                    // Handle the response as needed
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Handle errors
                }
            });
        });
    </script>
@endsection
