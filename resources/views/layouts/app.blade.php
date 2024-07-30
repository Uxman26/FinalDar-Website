<html>

<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('/logo.png') }}">
    <meta property="og:image" content="{{ url('/logo.png') }}">

    <!-- External CSS libraries -->

    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset(mix('assets/vendor/fonts/boxicons.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/vendor/css/core.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/vendor/css/theme-default.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/css/demo.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')) }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Title -->
    @stack('title')

</head>

<body>

    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <!-- Hide app brand if navbar-full -->
                <div class="app-brand demo">
                    <a href="{{ url('/') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="/logo-raw.jpg" alt="Logo" style="width: 100px; background-color: var(--bs-primary); border-radius: 20%;">
                        </span>

                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                
                <ul class="menu-inner py-1">
                    @foreach ($menuData[0]->menu as $menu)
                    {{-- menu headers --}}
                    @if (isset($menu->menuHeader))
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                    </li>
                    @else
                    {{-- active menu method --}}
                    @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();

                    if ($currentRouteName === $menu->slug) {
                    $activeClass = 'active';
                    } elseif (isset($menu->submenu)) {
                    if (is_array($menu->slug)) {
                    foreach ($menu->slug as $slug) {
                    if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                    $activeClass = 'active open';
                    }
                    }
                    } else {
                    if (str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0) {
                    $activeClass = 'active open';
                    }
                    }
                    }
                    @endphp
                    {{-- main menu --}}
                    <li class="menu-item {{ $activeClass }}">
                        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
                            @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                            @endisset
                            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                            @isset($menu->badge)
                            <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
                            @endisset
                        </a>
                        {{-- submenu --}}
                        @isset($menu->submenu)
                        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                        @endisset
                    </li>

                    @endif
                    @endforeach

                </ul>
                
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
            </aside>
            @yield('content')
        </div>
    </div>

    <!-- laravel style -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
    <script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
    <script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
    <script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
    <script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
    @yield('vendor-script')
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset(mix('assets/js/main.js')) }}"></script>

    <!-- END: Theme JS-->
    <!-- Pricing Modal JS-->
    @stack('pricing-script')
    <!-- END: Pricing Modal JS-->
    <!-- BEGIN: Page JS-->
    @yield('page-script')
    <!-- END: Page JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#language-switcher').on('change', function() {
                $('#language-switcher-form').submit()
            })
        })
    </script>
</body>

</html>