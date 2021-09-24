<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font awesome -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script defer src="{{ asset('js/all.js') }}"></script>

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.js') }}" defer></script>
    <link href="{{ asset('js/jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet">

    <script src="{{ asset('js/jquery.tmpl.js') }}" defer></script>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="{{ url('/home') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="121.3" height="23" viewBox="0 0 1213 230">
                        <image x="10" y="6" width="1191" height="216" xlink:href="data:img/png;base64,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"/>
                    </svg>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                            <div class="btn-group btn-group-sm">
                                <a href="/bms/basic" class="btn btn-outline-primary"><i class="fa fa-keyboard"></i> {{ __('strings.lb_bms_basic') }}</a>
<!--                                <a href="/bms/editor" class="btn btn-outline-primary"><i class="fa fa-newspaper"></i> {{ __('strings.lb_editor') }}</a>-->
                                <a href="/bms/settings" class="btn btn-outline-primary"><i class="fa fa-cog"></i> {{ __('strings.lb_bms_setting') }}</a>
                                <a href="/bms/subjects" class="btn btn-outline-primary"><i class="fa fa-book-open"></i> {{ __('strings.lb_subject_manage') }}</a>
                                <a href="/bms/hworks" class="btn btn-outline-primary"><i class="fa fa-book"></i> {{ __('strings.lb_bms_hworks_manage') }}</a>
                                <a href="/bms/pageSetting" class="btn btn-outline-primary"><i class="fa fa-scroll"></i> {{ __('strings.lb_page_setting') }}</a>
                                <a href="/bms/sending" class="btn btn-outline-primary"><i class="fa fa-plane"></i> {{ __('strings.lb_bms_sending') }}</a>
                                @if (\Illuminate\Support\Facades\Auth::user()->power != \App\Models\Configurations::$USER_POWER_TEACHER)
                                <a href="/bms/bbs" class="btn btn-outline-primary"><i class="fa fa-list"></i> {{ __('strings.lb_bms_bbs') }}</a>
                                @endif
                            </div>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Sign-up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item mt-1 mr-2">
                                <a href="/SmsFront" class="btn btn-dark btn-sm"><i class="fa fa-sms"></i> {{ __('strings.lb_sms_work_menu') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item  text-secondary" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
</html>
