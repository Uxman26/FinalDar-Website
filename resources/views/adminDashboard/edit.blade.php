@extends('layouts.app')
@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');

@endphp
@push('title')
<title>@lang('Role')</title>
@endpush
@section('content')
<div class="container-fluid p-4">
  
    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Permission</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('update.permission')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="hidden"  name="user_id" value="{{$user_info->id}}">
                            <input type="text" class="form-control" value="{{$user_info->name}}" readonly>
                        </div>
                        <div class="mb-3">
                            <p>Permissions Name</p>
                            @foreach ($permissions as $permission)
                            <input type="checkbox" {{($user_info->hasPermissionTo($permission->name))?"checked":''}} value="{{$permission->name}}" name="permission[]"> {{$permission->name}},<br>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Update Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection