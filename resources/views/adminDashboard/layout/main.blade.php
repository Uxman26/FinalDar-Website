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


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset(mix('assets/vendor/fonts/boxicons.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/vendor/css/core.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/vendor/css/theme-default.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/css/demo.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')) }}" rel="stylesheet">

    <!-- Title -->
    @stack('title')

</head>

<body>


    <!-- Hide app brand if navbar-full -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <!-- Hide app brand if navbar-full -->
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    @include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('variables.templateName') }}</span>
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
    </aside>

    <!-- Main content -->
    




</body>

</html>