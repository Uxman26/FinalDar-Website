@extends('layouts.app')

@section('content')
    @push('title')
        <title>@lang('Listing Details')</title>
        <style>
            .carousel-item img {
                height: 400px;
                width: auto;
                object-fit: contain;
            }

            #mediaCarousel {
                height: 400px;
                overflow: hidden;
            }

            #map {
                height: 400px;
                width: 100%;
            }
        </style>
    @endpush

    <!-- Scripts -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbPIrXFERaxgSurR_7wxbI-UdLRLTc94w&libraries=places&callback=initMap">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

    <script>
        // function initMap() {
        //     const latLng = {!! json_encode($listing->lat_lng) !!};
        //     let lat = 19.9625593;
        //     let lng = 56.2313026;

        //     if (latLng && latLng.length > 0) {
        //         const loc = JSON.parse(latLng);
        //         lat = loc.lat;
        //         lng = loc.lng;
        //     }

        //     const map = new google.maps.Map(document.getElementById('map'), {
        //         center: { lat, lng },
        //         zoom: 10
        //     });

        //     const marker = new google.maps.Marker({
        //         map: map,
        //         draggable: true,
        //         animation: google.maps.Animation.DROP,
        //         position: { lat, lng }
        //     });

        //     // Add event listener to the marker to get the address when it is dropped
        //     marker.addListener('dragend', function() {
        //         getMarkerAddress(marker.getPosition());
        //     });
        // }
        function initMap() {
            let response = JSON.parse($('#lat_lng').val());
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

        $media = json_decode($listing->media, true); // Decode as an array
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($media)) {
            Log::error('JSON decoding error: ' . json_last_error_msg());
            $media = []; // Set default empty array if there's an error
        }
    @endphp

    @if ($usr && $usr->can('view_listing'))
        <div class="container my-4">
            <h5 class="pb-1 mb-4">@lang('Listing Details')</h5>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <!-- Each "div.col" is a card for a listing detail -->
                <!-- KROKI NO -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-barcode fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Kroki No')</h5>
                                    <p class="card-text">{{ $listing->serial_no }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LISTING TITLE -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-sign fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Listing Title')</h5>
                                    <p class="card-text">{{ $listing->title }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIZE -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-ruler-combined fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Size')</h5>
                                    <p class="card-text">{{ $listing->size }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LOCATION -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-map-marker-alt fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Location')</h5>
                                    <p class="card-text">{{ $listing->location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATUS -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-thermometer-half fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Status')</h5>
                                    <p class="card-text">{{ $listing->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROPERTY TYPE -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-building fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Property Type')</h5>
                                    <p class="card-text">{{ $listing->type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- LAND TYPE -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-building fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Land Type')</h5>
                                    <p class="card-text">{{ $listing->land_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- AMOUNT -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-dollar-sign fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Amount')</h5>
                                    <p class="card-text">{{ $listing->amount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NO OF BEDROOMS -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-bed fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('No of Bedrooms')</h5>
                                    <p class="card-text">{{ $listing->no_bedrooms }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NO OF TOILETS -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-toilet fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('No of Toilets')</h5>
                                    <p class="card-text">{{ $listing->no_toilets }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NO OF MAJLIS -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-couch fa-3x"></i> <!-- Example icon -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('No of Majlis')</h5>
                                    <p class="card-text">{{ $listing->no_majlis }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NO OF FLOORS -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-building fa-3x"></i> <!-- Example icon for floors -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('No of Floors')</h5>
                                    <p class="card-text">{{ $listing->no_floors }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NO OF KITCHENS -->
                <div class="col">
                    <div class="card mb-3 h-100">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <i class="fas fa-utensils fa-3x"></i> <!-- Example icon for kitchens -->
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('No of Kitchens')</h5>
                                    <p class="card-text">{{ $listing->no_kitchens }}</p>
                                    <input type="hidden" value="{{ $listing->lat_lng }}" id="lat_lng">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div id="map" class="col mb-3" style="height: 400px;"></div>

                <!-- Media Carousel -->
                <div id="mediaCarousel" class="carousel slide col mb-3" data-bs-ride="carousel">
                    <!-- Carousel Indicators -->
                    @if (count($media) > 0)
                        <ol class="carousel-indicators">
                            @foreach ($media as $index => $mediaItem)
                                <li data-bs-target="#mediaCarousel" data-bs-slide-to="{{ $index }}"
                                    class="{{ $index == 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                    @endif

                    <!-- Carousel Inner -->
                    <div class="carousel-inner">
                        @foreach ($media as $index => $mediaItem)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                @if (isset($mediaItem['type']) && ($mediaItem['type'] == 'image/jpeg' || $mediaItem['type'] == 'image/png'))
                                    <img src="{{ $mediaItem['path'] }}" class="d-block w-100"
                                        alt="Slide {{ $index + 1 }}">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Carousel Controls -->
                    @if (count($media) > 0)
                        <a class="carousel-control-prev" href="#mediaCarousel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#mediaCarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid p-4">
            <p>You don't have permission to view this page</p>
        </div>

    @endif
    <script>
        $(document).ready(function() {
            let response = $('#lat_lng').val();
            console.log(response);
            initMap(response);
        })
    </script>
@endsection
