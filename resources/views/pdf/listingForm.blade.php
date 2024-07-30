<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('Listing Details')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<script>
        // JavaScript to open print settings when the page loads
        window.onload = function() {
            window.print();
        };
    </script>

<div class="mx-3 my-3" >

 <div>
    <img src="/logo-bw.png" style="width:100px;border-radius:5px;border:1px solid black" />
 </div>
  
<div class="d-flex mt-5 w-100" style="font-size:14px;" >
 <div style="min-width:200px" class="col-10" >
    <b>Date:</b>
    <span style="text-decoration-line:underline" >{{date_format($listing->created_at,'d M Y')}}</span>
 </div>
 
  <div class="col-2" >
   <b>Kroki No:</b>
   <span style="text-decoration-line:underline" >{{$listing->serial_no}}</span>
  </div>

</div>

<!-- Block 2 Start -->
<div class="d-flex w-100 container-fluid mt-5" >
    
    <div class="col-4" >
        <b>Listing Title:</b>
        <span style="text-decoration-line:underline" >{{$listing->title}}</span>
    </div>


</div>
<!-- Block 2 End -->

<!-- Block 2 Start -->
<div class="d-flex w-100 container-fluid mt-5" >

    <div class="col-4" >
       <b>Client:</b>
       <span style="text-decoration-line:underline" >{{$full_name}}</span>
    </div>
    
    <div  class="col-2" >
     <b>Size:</b>
     <span style="text-decoration-line:underline" >{{$listing->size}}</span>
    </div>


    <div class="col-3" >
     <b>Status:</b>
     <span style="text-decoration-line:underline" >{{$listing->status}}</span>
    </div>

    <div class="col-3" >
    <b>Amount:</b>
    <span style="text-decoration-line:underline" >{{$listing->amount}}</span>
 </div>


</div>
<!-- Block 2 End -->


<!-- Block 3 Start -->
<div class="d-flex w-100 container-fluid mt-5" >
    
    <div class="col-5" >
       <b>Location:</b>
       <span style="text-decoration-line:underline" >{{$listing->location}}</span>
    </div>

    <div class="col-4" >
    <b>Listing&nbsp;Type:</b>
    <span style="text-decoration-line:underline" >{{$listing->type}}</span>
 </div>

 <div class="col-3" >
    <b>No Of Bedrooms:</b>
    <span style="text-decoration-line:underline" >{{$listing->no_bedrooms}}</span>
 </div>
 
</div>
<!-- Block 3 End -->

<!-- Block 4 Start -->
<div class="d-flex w-100 container-fluid mt-5" >



<div class="col-3" >
   <b>No Of Toilets:</b>
   <span style="text-decoration-line:underline" >{{$listing->no_toilets}}</span>
</div>


 <div class="col-3" >
    <b>No Of Majlis:</b>
    <span style="text-decoration-line:underline" >{{$listing->no_majlis}}</span>
 </div>

 <div class="col-3" >
    <b>No Of Floors:</b>
    <span style="text-decoration-line:underline" >{{$listing->no_floors}}</span>
 </div>

 <div class="col-3" >
    <b>No Of Kitchens:</b>
    <span style="text-decoration-line:underline" >{{$listing->no_kitchens}}</span>
 </div> 



</div>
<!-- Block 4 End -->

</div>


</body>
</html>