@extends('layouts.blankLayout')

@section('title', 'Login')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Account Section -->
            <section class="card">
                <div class="card-body">
                    <div class="accounts-inner__wrapper bg--section">
                        <div class="d-flex d-lg-none my-3 justify-content-center align-items-center w-100">
                            <img src="/logo.png" style="width:40%;border-radius:10px;border:1px solid orange" />
                        </div>
                        <div class="accounts-left">
                            <div class="accounts-left-content">
                                <!-- Logo -->
                                <div class="app-brand justify-content-center">
                                    <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                        <img src="{{ asset('logo-raw.jpg') }}" alt="Logo" style="width: 150px; background-color: var(--bs-primary); border-radius: 50%;">

                                    </a>
                                </div>
                                <!-- /Logo -->

                                <div class="section-header">
                                    <h6 class="section-header__subtitle"></h6>
                                    <h3 class="section-header__title">@lang('Login')</h3>

                                </div>
                                <form class="row gy-4" id="loginfor" action="{{ url('/login') }}" method="post">
                                    <div class="alert alert-success alert-dismissible fade show" style="display: none;">
                                        <p class="m-0 text-success"></p>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible fade show" style="display: none;" role="alert">
                                        <p class="m-0 text-danger"></p>
                                    </div>
                                    @if(session()->has('success'))
                                    <span class="text--base text-center">
                                        {{ session()->get('success') }}
                                    </span>
                                    @endif
                                    @if(session()->has('error'))
                                    <span class="text--base text-center">
                                        {{ session()->get('error') }}
                                    </span>
                                    @endif
                                    @csrf
                                    <div class="col-sm-12">
                                        <label for="email" class="form-label">@lang('UserName Or Email')</label>
                                        @error('email')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{ $message }}</label>
                                        @enderror
                                        <input type="text" name="email" id="email" class="form-control">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="password" class="form-label">@lang('Your Password')</label>
                                        @error('password')
                                        <label style="color:red;font-size:0.7rem" for="fullname-error" class="form-label text-sm ">{{ $message }}</label>
                                        @enderror
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary d-grid w-100">@lang('Sign In') <div class="spinner-borde"></div></button>
                                    </div>
                                </form>
                                <div class="content-wrapper">
                                    <div class="container-xxl flex-grow-1 container-p-y">
                                        <form id="language-switcher-form" action="{{ url('/change-language') }}" method="POST" class="d-flex w-100 justify-content-end mb-3">
                                            @csrf
                                            <select id="language-switcher" name="language" class="form-control" style="width:fit-content">
                                                @if(app()->getLocale() === 'ar')
                                                <option value="ar">@lang('Arabic')</option>
                                                <option value="en">@lang('English')</option>
                                                @else
                                                <option value="en">@lang('English')</option>
                                                <option value="ar">@lang('Arabic')</option>
                                                @endif
                                            </select>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
@endsection