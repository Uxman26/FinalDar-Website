@extends('layouts.app')
@section('content')
    @push('title')
        <title>Google Map</title>
        <style>
            /* Set map container size */
            #map {
                height: 500px;
                width: 100%;
            }
        </style>
    @endpush


    <!-- Display map container -->
    {{-- <div class="container-fluid mt-5">
        <div class="form-group">
            <input type="file" name="image" id="imageInput" accept="image/*">
        </div>
        <br> --}}
        <div id="map"></div>
    {{-- </div> --}}
    {{-- @dd($listings) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Initialize Google Map 656596.79 2611143.99
        function initMap(longitude, latitude) {
            // Create a map object and specify the DOM element for display.
            const map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 0,
                    lng: 0
                }, // Default center
                zoom: 2 // Default zoom level
            });

            // Get listings data from Blade view
            const listings = @json($listings);

            // Loop through listings and add markers to the map
            listings.forEach(listing => {


                let {
                    lat,
                    lng
                } = JSON.parse(listing.lat_lng);


                // Determine pin color based on listing type
                let pinColor = 'red';
                if (listing.type === 'Appartment') {
                    pinColor = 'green';
                }

                // Create marker for each listing with appropriate pin color

                if (longitude !== undefined && latitude !== undefined) {
                    lat = latitude;
                    lng = longitude;
                } 
console.log(lat, lng);
                const marker = new google.maps.Marker({
                    position: {
                        lat,
                        lng
                    },
                    map,
                    title: listing.title, // Optional: Display listing title as marker title
                    icon: {
                        url: `http://maps.google.com/mapfiles/ms/icons/${pinColor}-dot.png`,
                        scaledSize: new google.maps.Size(32, 32)
                    }
                });

                // Add click event listener to each marker
                marker.addListener('click', function() {
                    // Redirect to the listing page
                    window.location.href = `http://127.0.0.1:8000/admin/view-listing/${listing.id}`;
                });
            });
        }


        // $('#imageInput').change(function() {
        //     var formData = new FormData();
        //     var file = $(this)[0].files[0];
        //     formData.append('image', file);

        //     $.ajax({
        //         url: "{{ url('/admin/extractText') }}",
        //         type: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             console.log('Text extracted:', response);
        //             initMap(response.longitude, response.latitude);
        //             // Handle the response as needed
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error:', error);
        //             // Handle errors
        //         }
        //     });
        // });
    </script>
    <!-- Include Google Maps API with callback to initMap() function -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbPIrXFERaxgSurR_7wxbI-UdLRLTc94w&callback=initMap"></script>
    </body>

    </html>
@endsection
