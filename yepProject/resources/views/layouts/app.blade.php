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

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                            @if (\Illuminate\Support\Facades\Auth::user()->power != \App\Models\Configurations::$USER_POWER_TEACHER)
                                <a href="/classes" class="btn btn-link text-warning">{{ __('strings.lb_ban_manage') }}</a>
                                <a href="#" class="btn btn-link text-warning">{{ __('strings.lb_hakgi_manage') }}</a>
                                <a href="#" class="btn btn-link text-warning">{{ __('strings.lb_testweek_manage') }}</a>
                                <a href="#" class="btn btn-link text-warning">{{ __('strings.lb_comment_manage') }}</a>
                                <a href="#" class="btn btn-link text-warning">{{ __('strings.lb_test_manage') }}</a>
                            @endif
                                <a href="#" class="btn btn-link text-warning">{{ __('strings.lb_student_manage') }}</a>
                                <a href="#" class="btn btn-link text-warning">{{ __('strings.lb_sms_work_manage') }}</a>
                        @endauth
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if (\App\Models\Configurations::$USER_POWER_ADMIN == \Illuminate\Support\Facades\Auth::user()->power)
                        <li class="nav-item mr-2">
                            <a href="/settings" class="btn btn-primary btn-sm mt-1"><i class="fa fa-cog"></i> {{ __('strings.lb_settings') }}</a>
                        </li>
                        @endif
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
