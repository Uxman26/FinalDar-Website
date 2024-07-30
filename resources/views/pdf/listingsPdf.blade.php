<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('Listings')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<script>
        // JavaScript to open print settings when the page loads
        window.onload = function() {
            window.print();
        };
    </script>
@if(count($listings) > 0)
<div>
<table class="table table-responsive">
  <thead class="thead-dark">
    <tr>
		 <th scope="col" >@lang('Client')</th>
		 <th scope="col" >@lang('Kroki&nbsp;No')</th>
		 <th scope="col" >@lang('Title')</th>
		 <th scope="col" >@lang('Size')</th>
		 <th scope="col" >@lang('Location')</th>
		 <th scope="col" >@lang('Type')</th>
		 <th scope="col" >@lang('Amount')</th>
		 <th scope="col" >@lang('Status')</th>
		 <th scope="col" >@lang('#&nbsp;Bedrooms')</th>
		 <th scope="col" >@lang('#&nbsp;Toilets')</th>
		 <th scope="col" >@lang('#&nbsp;Majlis')</th>
		 <th scope="col" >@lang('#&nbsp;Floors')</th>
		 <th scope="col" >@lang('#&nbsp;Kitchens')</th>
		 <th scope="col" >@lang('Date')</th>
		</tr>
  </thead>
  <tbody class="bg-white" >
    @foreach($listings as $listing)
    <tr>
      <td >{{$listing->first_name}} {{$listing->middle_name}} {{$listing->last_name}}</td>
      <td >{{$listing->serial_no}}</td>
      <td >{{$listing->title}}</td>
      <td >{{$listing->size}}</td>
      <td >{{$listing->location}}</td>
      <td >{{$listing->type}}</td>
      <td >{{$listing->amount}}</td>
      <td >{{$listing->status}}</td>
	  <td >{{$listing->title}}</td>
	  <td >{{$listing->no_toilets}}</td>
	  <td >{{$listing->no_majlis}}</td>
	  <td >{{$listing->no_floors}}</td>
	  <td >{{$listing->no_kitchens}}</td>
	  <td >{{date_format($listing->created_at,'d M Y')}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
@else
 <div class="d-flex justify-content-center align-items-center w-100" style="height: calc(100vh - 500px)" >
   <h4>@lang('No Listings Found')!</h4>
 </div>
@endif

</body>
</html>