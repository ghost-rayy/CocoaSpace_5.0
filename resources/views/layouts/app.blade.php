<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CocoaSpace</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body, html {
            background-image: url('{{ asset('images/signin.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            width: 100vw;
            margin: 0;
            overflow-y: auto; /* Allow vertical scrolling */
            display: flex;
            flex-direction: column;
        }

        #app {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            align-items: stretch;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: transparent;
            padding-top: 60px;
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            font-size: 20px;
        }

        .sidebar a:hover {
            background-color: rgb(255, 255, 255);
            color: rgb(134, 64, 7);
            border-radius: 50px;
            font-weight: bold;
        }

        .content {
            margin-left: 250px; /* To prevent overlap with the sidebar */
            padding: 20px;
            flex-grow: 1;
            min-height: 100vh;
            background-color: transparent;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                width: 200px;
                left: -200px;
                top: 0;
                height: 100vh;
                background-color: rgba(0,0,0,0.8);
                padding-top: 60px;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding: 10px;
            }

            #app {
                flex-direction: column;
            }
        }
    </style>

</head>
    <body>
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">☰</button>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light">
                <div class="container">
                    <a class="navbar-brand">
                        {{-- <img src="{{ asset('images/cocoa space.png') }}" alt="CocoaSpace Logo" style="height: 200px; width: 300px; margin-left:-250px; margin-top:-40px;"> --}}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto"></ul>

                        <!-- Right Side Of Navbar -->
                        {{-- <ul class="navbar-nav ms-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
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
                        </ul> --}}
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="content">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
        <script>
            function toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('active');
            }
        </script>
    </body>
</html>
