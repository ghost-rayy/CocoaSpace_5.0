<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CocoaSpace</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-width-mobile: 200px;
            --primary-color: rgb(134, 64, 7);
            --hover-color: rgb(255, 255, 255);
        }

        body, html {
            background-image: url('{{ asset('images/signin.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Nunito', sans-serif;
        }

        #app {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            align-items: stretch;
            position: relative;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.8);
            padding-top: 60px;
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: all 0.3s ease;
            border-radius: 50px;
            margin: 5px 10px;
        }

        .sidebar a:hover {
            background-color: var(--hover-color);
            color: var(--primary-color);
            font-weight: bold;
            transform: translateX(5px);
        }

        .content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            flex-grow: 1;
            min-height: 100vh;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .sidebar-toggle-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary-color);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-width-mobile);
                left: calc(-1 * var(--sidebar-width-mobile));
                background-color: rgba(0, 0, 0, 0.9);
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding: 15px;
            }

            .sidebar-toggle-btn {
                display: block;
            }

            .overlay.active {
                display: block;
            }

            #app {
                flex-direction: column;
            }

            .navbar-brand img {
                max-width: 200px;
                height: auto;
                margin: 0;
            }
        }

        /* Additional responsive breakpoints */
        @media (max-width: 480px) {
            .content {
                padding: 10px;
            }

            .sidebar {
                width: 100%;
                left: -100%;
            }

            .sidebar a {
                padding: 12px 15px;
                font-size: 16px;
            }
        }
    </style>

</head>
    <body>
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">☰</button>
        <div class="overlay" onclick="toggleSidebar()"></div>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light">
                <div class="container">
                    <a class="navbar-brand">
                        {{-- <img src="{{ asset('images/cocoa space.png') }}" alt="CocoaSpace Logo" class="img-fluid"> --}}
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
                const overlay = document.querySelector('.overlay');
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const sidebar = document.querySelector('.sidebar');
                const sidebarToggle = document.querySelector('.sidebar-toggle-btn');
                const overlay = document.querySelector('.overlay');
                
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(event.target) && 
                    !sidebarToggle.contains(event.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.overlay');
                
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        </script>
    </body>
</html>
