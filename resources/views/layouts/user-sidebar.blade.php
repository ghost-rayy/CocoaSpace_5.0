<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CocoaSpace</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <style>
            /* html, body {
                overflow: hidden;
            } */
            .navbar {
                width:100%;
                height:100%;
                background-color: #42CCC5; 
                display: flex; 
                align-items: center; 
                justify-content: space-between; 
                padding: 0.001rem 0.5rem; 
            }

             .navbar-left img{
                width: 70%;
                height: 70%;
            }

            .dropdown-toggle-wrapper {
                cursor: pointer; 
                display: flex; 
                align-items: center; 
                gap: 0.5rem; 
                color: white; 
                font-weight: 600;
                width: 100%;
                height: 50%;
            }

            .dropdown-toggle-wrapper:hover {
                background-color: white;
                padding: 10px;
                border-radius:10px;
                color: aqua;
            }

            .custom-dropdown-menu {
                position: absolute; 
                right: 1rem; 
                top: 5rem; 
                background: white; 
                border-radius: 4px; 
                box-shadow: 0 2px 8px rgba(0,0,0,0.15); 
                display: none; 
                flex-direction: column; 
                min-width: 120px; 
                z-index: 1000;
                border-radius: 10px;
            }

            .dropdown-logout {
                padding: 0.5rem 1rem; 
                color: #333; 
                text-decoration: none; 
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                border-bottom: 1px solid #eee;
                border-radius: 10px;
            }

            .dropdown-logout:hover {
                background-color: rgb(240, 255, 255);
            }
        </style>
    </head>

    <body>
        <div class="main-content">
            <div class="navbar">
                <div class="navbar-left">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo" style="">
                </div>
                <div class="navbar-right">
                    <div class="dropdown-toggle-wrapper" onclick="toggleDropdown()" style="">
                        <span class="profile-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-user-circle user-icon" style="font-size: 1.5rem;"></i>
                        <i class="fas fa-caret-down dropdown-icon" style="font-size: 1rem; margin-left: 0.3rem;"></i>
                    </div>
                    <div class="custom-dropdown-menu" id="logoutDropdown" style="">
                        <a href="#" class="dropdown-logout" onclick="confirmLogout(event)" style="">
                            Logout<i class="fas fa-sign-out-alt logout-icon"></i>
                        </a>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

        </div>


        <div class="content">
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </body>
</html>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('logoutDropdown');
        dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
    }

        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

    // Optional: Close dropdown if clicked outside
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('logoutDropdown');
        const toggle = document.querySelector('.dropdown-toggle-wrapper');
        if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>

