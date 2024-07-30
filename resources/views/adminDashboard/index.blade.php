@extends('layouts.app')
@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');

@endphp
@push('title')
<title>@lang('Dashboard')</title>
@endpush
@section('content')
<div class="layout-page">

  <nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
    @if(isset($navbarDetached) && $navbarDetached == '')
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
          <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
        </a>
      </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="bx bx-menu bx-sm"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item d-flex align-items-center">
            <i class="bx bx-search fs-4 lh-0"></i>
            <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..." aria-label="Search...">
          </div>
        </div>
        <!-- /Search -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">

          <!-- Place this tag where you want the button to render. -->
          <li class="nav-item lh-1 me-3">
            <a class="github-button" href="https://github.com/themeselection/sneat-html-laravel-admin-template-free" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-laravel-admin-template-free on GitHub">Star</a>
          </li>

          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <span class="fw-medium d-block">
                        @php
                          if(session()->has('user')) {
                              $user = session()->get('user');
                              $email =$user['user_name'];
                          } else {
                              $email = null;
                          }
                      @endphp

                      {{ $email }}
                    </span>
                      <small class="text-muted">Admin</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <i class="bx bx-user me-2"></i>
                  <span class="align-middle">My Profile</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <i class='bx bx-cog me-2'></i>
                  <span class="align-middle">Settings</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <span class="d-flex align-items-center align-middle">
                    <i class="flex-shrink-0 bx bx-credit-card me-2 pe-1"></i>
                    <span class="flex-grow-1 align-middle">Billing</span>
                    <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                  </span>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <i class='bx bx-power-off me-2'></i>
                  <span class="align-middle">Log Out</span>
                </a>
              </li>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      @if(!isset($navbarDetached))
    </div>
    @endif
  </nav>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>@lang('Vip Clients')</span>
                  <div class="d-flex align-items-end mt-2">
                    <h3 class="mb-0 me-2" style="font-weight:bold">{{$total_vip_clients}}</h3>
                  </div>
                  <small>@lang('Total Vip Clients')</small>
                </div>
                <span class="badge bg-label-primary rounded p-2">
                  <i class="fas fa-crown"></i>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>@lang('General Clients')</span>
                  <div class="d-flex align-items-end mt-2">
                    <h3 class="mb-0 me-2" style="font-weight:bold">{{$total_org_clients}}</h3>
                  </div>
                  <small>@lang('Total General Clients')</small>
                </div>
                <span class="badge bg-label-primary rounded p-2">
                  <i class="fas fa-user"></i>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>@lang('Listing On Sell')</span>
                  <div class="d-flex align-items-end mt-2">
                    <h3 class="mb-0 me-2" style="font-weight:bold">{{$total_onsell_listings}}</h3>
                  </div>
                  <small>@lang('Total Listings on Sell')</small>
                </div>
                <span class="badge bg-label-primary rounded p-2">
                  <i class="fas fa-map"></i>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div class="content-left">
                  <span>@lang('Listing Sold')</span>
                  <div class="d-flex align-items-end mt-2">
                    <h3 class="mb-0 me-2" style="font-weight:bold">{{$total_sold_listings}}</h3>
                  </div>
                  <small>@lang('Total Listings Sold')</small>
                </div>
                <span class="badge bg-label-primary rounded p-2">
                  <i class="fas fa-map"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Listing details -->

      <div class="card">
        <h5 class="card-header">@lang('Latest Listings')</h5>
        @if(count($latest_listings) > 0)
        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>@lang('Kroki No')</th>
                <th>@lang('Title')</th>
                <th>@lang('Land Type')</th>
                <th>@lang('Land Size')</th>
                <th>@lang('Date')</th>
                <th>@lang('Actions')</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @foreach($latest_listings as $listing)
              <tr>
                <td>{{$listing->serial_no}}</td>
                <td>{{$listing->title}}</td>
                @if($listing->type === 'Apartment')
                <td>@lang('Apartment')</td>
                @elseif($listing->type === 'Land')
                <td>@lang('Land')</td>
                @else
                <td>@lang('Villa')</td>
                @endif
                <td>{{$listing->size}}</td>
                <td>{{date_format($listing->created_at,'d M Y')}}</td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{url('/admin/view-listing')}}/{{$listing->id}}"><i class="fas fa-eye me-1" style="font-size:14px"></i> @lang('View Listing')</a>
                      <form id="listing-form-{{$listing->id}}" data-listing-id="{{$listing->id}}" onSubmit="DeleteListing(this)" action="{{url('/admin/delete-listing')}}" method="post"> @csrf
                        <input type="hidden" name="id" value="{{$listing->id}}" />
                        <button class="dropdown-item" type="submit"><i id="form-btn-text-{{$listing->id}}" class="fas fa-trash me-1" style="font-size:14px"></i> @lang('Delete Client') <div id="loader-{{$listing->id}}" class="spinner-border d-none" style="width:14px;height:14px" role="status"><span class="sr-only">Loading...</span></div></button>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="d-flex justify-content-center align-items-center w-100" style="height: calc(100vh - 600px)">
          <h4>@lang('No Listings Found!')</h4>
        </div>
        @endif
      </div>
      <div class="row g-4 mb-4">
        <span>
          <!-- Cards for displaying statistics -->
      </div>
      <!-- Client details -->
      <div class="card">
        <h5 class="card-header">@lang('Latest Clients')</h5>
        @if(count($latest_clients) > 0)
        <div style="overflow-x:scroll">
          <table class="table table-responsive">
            <thead class="thead-dark">
              <tr>
                <th scope="col">@lang('Client Name')</th>
                <th scope="col">@lang('Type')</th>
                <th scope="col">@lang('Email')</th>
                <th scope="col">@lang('Phone')</th>
                <th scope="col">@lang('Date')</th>
                <th scope="col">@lang('Actions')</th>
              </tr>
            </thead>
            <tbody class="bg-white">
              @foreach($latest_clients as $client)
              <tr>
                <td style="min-width:200px">{{$client->first_name}}&nbsp;{{$client->middle_name}}&nbsp;{{$client->last_name}}</td>
                <td style="min-width:50px">
                  @if($client->is_vip === 'yes')
                  <div title="VIP" style="font-weight:bold;background-color:#800080;border-radius:100%;color:white;width:fit-content;padding:3px 8px 5px 8px"><i class="fas fa-crown" style="font-size:12px"></i></div>
                  @else
                  <div title="Normal" style="font-weight:bold;background-color:orange;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px"><i class="fas fa-user" style="font-size:14px"></i></div>
                  @endif
                </td>
                <td style="min-width:100px">{{$client->email}}</td>
                <td style="min-width:150px">{{$client->phone}}</td>
                <td style="min-width:100px">{{date_format($client->created_at,"d M Y")}}</td>
                <td>
                  <div class="d-flex justify-content-center">
                    <a title="View Client" href="{{url('/admin/view-client')}}/{{$client->id}}" style="border:none;font-weight:bold;background-color:orange;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px"><i class="fas fa-eye" style="font-size:14px"></i></a>
                    <form id="client-form-{{$client->id}}" data-client-id="{{$client->id}}" onSubmit="DeleteClient(this)" action="{{url('/admin/delete-client')}}" method="post" class="mx-2"> @csrf
                      <input type="hidden" name="id" value="{{$client->id}}" />
                      <button title="Delete Client" style="border:none;font-weight:bold;background-color:red;border-radius:100%;color:white;width:fit-content;padding:3px 9px 5px 9px">
                        <i id="form-btn-text-{{$client->id}}" class="fas fa-trash" style="font-size:14px"></i>
                        <div id="loader-{{$client->id}}" class="d-none">
                          <div class="spinner-border" style="width:14px;height:14px" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="d-flex justify-content-center align-items-center w-100" style="height: calc(100vh - 600px)">
          <h4>@lang('No Clients Found!')</h4>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection