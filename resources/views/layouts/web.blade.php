<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('head_title', config('app.name'))</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav" id="mainNavbar">
                        <li class="nav-item {{ active('web.index') }}">
                            <a class="nav-link" href="{{ route('web.index') }}">
                                {{ __('Home') }}
                            </a>
                        </li>
                        <li class="nav-item {{ active('web.events') }}">
                            <a class="nav-link" href="{{ route('web.events.nearest') }}">
                                {{ __('Nearest Events') }}
                            </a>
                        </li>
                        @auth
                        <li class="nav-item {{ active('panel.events') }}">
                            <a class="nav-link" href="{{ route('panel.events.index') }}">{{ __('My Events') }}</a>
                        </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   title="Authenticated as {{ Auth::user()->email }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>
    @auth()
        <script src="{{ mix('js/panel/app.js') }}" defer></script>
    @endauth

    @yield('footer_scripts')
</body>
</html>
