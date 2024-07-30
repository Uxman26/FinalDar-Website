<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('Clients')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> -->
</head>
<script>
        // JavaScript to open print settings when the page loads
        window.onload = function() {
            window.print();
        };
    </script>
<body >
@if(count($clients) > 0)
<div style="height:100vh;background-color:white" id="printarea" >
<table class="table table-responsive">
  <thead class="thead-dark">
    <tr>
	 <th scope="col" >@lang('Full&nbsp;Name')</th>
	 <th scope="col" >@lang('Is Vip')?</th>
	 <th scope="col" >@lang('Phone')</th>
	 <th scope="col" >@lang('Email')</th>
	 <th scope="col" >@lang('Address')</th>
	 <th scope="col" >@lang('Date')</th>
	</tr>
  </thead>
  <tbody class="bg-white" >
    @foreach($clients as $client)
    <tr>
      <td >{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}</td>
      <td>
      @if($client->is_vip === 'yes')
      @lang('Yes')
      @else
      @lang('No')
      @endif
      </td>
      <td >{{$client->phone}}</td>
      <td >{{$client->email}}</td>
      <td >{{$client->address}}</td>
      <td >{{date_format($client->created_at,'d M Y')}}</td>
    @endforeach
  </tbody>
</table>
</div>
@else
 <div class="d-flex justify-content-center align-items-center w-100" style="height: calc(100vh - 500px)" >
   <h4>@lang('No Clients Found')!</h4>
 </div>
@endif

</body>
</html>