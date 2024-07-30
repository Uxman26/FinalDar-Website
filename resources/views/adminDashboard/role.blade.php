@extends('layouts.app')
@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

@php
      $usr = session()->get('user');
      if ($usr && is_array($usr)) {
      $usr = \App\Models\Admin::find($usr['id']);
    }
@endphp

@push('title')
<title>@lang('Role')</title>
@endpush
@section('content')
<div class="container-fluid p-4">
@if( $usr && $usr->can('role_management'))
<div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h3>Role List</h3>
                    @if (session('assaign_role'))
                        <div class="alert alert-success"><strong>{{session('assaign_role')}}</strong></div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="no-more-tables">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            {{--<th>Action</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key=>$role)
                            <tr>
                                <td data-title="SL">{{$key+1}}</td>
                                <td data-title="Role Name">{{$role->name}}</td>
                                <td data-title="Permissions">
                                    @foreach ($role->getPermissionNames() as $permission)

                                            {{$permission}}

                                    @endforeach
                                </td>
                                <td data-title="Action">
                                    <a href="{{route('edit.permission', $role->id)}} " class="btn btn-info"><i class="fa fa-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3>User List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="no-more-tables">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>User Name</th>
                            <th>Roles</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key=>$user)
                            <tr>
                                <td data-title="SL">{{$key+1}}</td>
                                <td data-title="User Name">{{$user->user_name}}</td>
                                <td data-title="Roles">
                                    @forelse ($user->getRoleNames() as $role)
                                        {{$role}},
                                        @empty
                                        Not Assaigned Yet
                                    @endforelse
                                </td>
                                <td data-title="Permissions">
                                    @forelse ($user->getAllPermissions() as $permission)
                                        {{$permission->name}},
                                        @empty
                                        User dont have any permission
                                    @endforelse
                                </td>
                                <td data-title="Action">
                                    <a href="{{route('edit.permissions', $user->id)}} " class="btn btn-primary btn-sm"><i class="fa fa-pen"></i></a>
                                    <a href="{{route('remove.role', $user->id)}}" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                    <div class="card-header">
                        <h3>Add Permission</h3>
                    </div>
                    <div class="card-body">
                        <h5>
                            @if (session('add_permission'))
                            <div class="alert alert-success"><strong>{{session('add_permission')}}</strong></div>
                          @endif
                        </h5>
                        <form action="{{route('add.permission')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" name="permission_name" placeholder="Enter Permission Name">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Add Permission</button>
                            </div>
                        </form>
                    </div>
            </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Add Role</h3>
                    </div>
                <div class="card-body">
                    <h5>
                        @if (session('add_role'))
                        <div class="alert alert-success"><strong>{{session('add_role')}}</strong></div>
                      @endif
                    </h5>
                    <form action="{{route('add.role')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="role_name" placeholder="Enter Role Name">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Select Permission</label><br>
                            @foreach ($permissions as $permission)
                            <input type="checkbox" value="{{$permission->name}}" name="permission[]"> {{$permission->name}},<br>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Add Role</button>
                        </div>
                    </form>
                </div>
            </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Assaign Role To User</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('assaign.role')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select name="user_id" class="form-control">
                                <option value="">--Select User--</option>
                             @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->user_name}}</option>
                             @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="role_id" class="form-control">
                                <option value="">--Select Role--</option>
                             @foreach ($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                             @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Assaign Role</button>
                        </div>
                    </form>
                </div>
        </div>
        </div>

    </div>

</div>
@else
    <p>You don't have permission to view this page</p>
@endif
@endsection