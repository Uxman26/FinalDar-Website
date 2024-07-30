
@extends('adminDashboard.layout.main')
@section('main')
@push('title')
<title>@lang('Change Password')</title>
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<script>
 function ChangePassword(){
  const form = new FormData()
  form.append('old-password',$('#old-password').val())
  form.append('password',$('#password').val())
  form.append('password_confirmation',$('#password_confirmation').val())
  $('#pass-update-text').css('display','none')
  $('#pass-update-loader').css('display','flex')
  $.ajax({
    url: "{{url('/admin/change-password')}}", // Replace with your server-side endpoint
    type: 'POST',
    data: form,
    processData: false,
    contentType: false,
    headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (res) {
      window.location.href = "{{url('/admin/dashboard')}}"
      $('#pass-update-text').css('display','flex')
      $('#pass-update-loader').css('display','none')
      toastr.success('Password Updated Successfully!')
    },
    error: function (error) {
      console.log(error)
      if(error.status === 422){
        const obj = error.responseJSON.errors;
        for(let prop in obj){
          toastr.error(obj[prop][0])
        }
      }else{
          toastr.error(error.responseJSON.message);
      }
      $('#pass-update-text').css('display','flex')
      $('#pass-update-loader').css('display','none')
    }
  })

 }

</script>


<div class="dashborad--content">
				<div class="breadcrumb-area">
    <h3 class="title">@lang('Change Password')</h3>
    <ul class="breadcrumb">
        <li>
            <a href="{{url('/user/dashboard')}}">@lang('Dashboard')</a>
        </li>
        <li>@lang('Change Password')</li>
    </ul>
</div>
@if(session()->has('success'))
<div>
 <h5 class="text-success text-center mb-2" >{{session()->get('success')}}</h5>
</div>
@endif
<div class="dashboard--content-item">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7 col-xxl-6">
            <div class="profile--card">
                <form id="request-form">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-sm-12">
                            <label for="new-password" class="form-label">@lang('Old Password')</label>
                            @error('password')
                                     <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>    
                                    @enderror
                            <input type="password" name="password" id="old-password" class="form-control"
                                placeholder="New Password" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="new-password" class="form-label">@lang('New Password')</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="New Password" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="confirm-password" class="form-label">@lang('Re-Type Password')</label>
                            @error('password_confirmation')
                                     <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{$message}}</label>    
                                    @enderror
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                placeholder="Confirm Password" required>
                        </div>
                        <div class="col-sm-12">
                        <button onclick="ChangePassword()" type="button" class="btn px-3 py-2 my-2" style="background-color:#D5924D;border-radius:5px;height:40px;color:white;style:font-size:12px !important">
      <p id="pass-update-text">@lang('Change')</p>
      <div id="pass-update-loader" style="display:none;justify-content:center;align-items:center;height:100%" >
        <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
      </div>
    </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection