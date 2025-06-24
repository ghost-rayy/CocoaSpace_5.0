<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>CocoaSpace</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <style>
            :root {
                --primary-color: #42CCC5;
                --hover-color: #FFFFFF;
                --text-color: #333333;
                --header-height: 70px;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Nunito', sans-serif;
                min-height: 100vh;
                overflow-x: hidden;
            }

            .main-content {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Navbar Styles */
            .navbar {
                width: 100%;
                height: var(--header-height);
                background-color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 1.5rem;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }

            .navbar-left {
                display: flex;
                align-items: center;
            }

            .navbar-left img {
                width: 100%;
                height: 80px;
                transition: all 0.3s ease;
            }

            .navbar-right {
                position: relative;
            }

            .dropdown-toggle-wrapper {
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 0.8rem;
                color: var(--hover-color);
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 25px;
                transition: all 0.3s ease;
            }

            .dropdown-toggle-wrapper:hover {
                background-color: var(--hover-color);
                color: var(--primary-color);
            }

            .profile-name {
                font-size: 1rem;
            }

            .user-icon {
                font-size: 1.5rem;
            }

            .dropdown-icon {
                font-size: 1rem;
                transition: transform 0.3s ease;
            }

            .dropdown-toggle-wrapper:hover .dropdown-icon {
                transform: rotate(180deg);
            }

            .custom-dropdown-menu {
                position: absolute;
                right: 0;
                top: calc(100% + 0.5rem);
                background: var(--hover-color);
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: none;
                flex-direction: column;
                min-width: 150px;
                z-index: 1001;
                overflow: hidden;
                transform-origin: top right;
                transform: scale(0.95);
                opacity: 0;
                transition: all 0.2s ease;
            }

            .custom-dropdown-menu.active {
                transform: scale(1);
                opacity: 1;
            }

            .dropdown-logout {
                padding: 0.8rem 1rem;
                color: var(--text-color);
                text-decoration: none;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s ease;
            }

            .dropdown-logout:hover {
                background-color: rgba(66, 204, 197, 0.1);
                color: var(--primary-color);
            }

            .logout-icon {
                margin-left: 0.5rem;
            }

            /* Content Area */
            .content {
                margin-top: var(--header-height);
                flex: 1;
                padding: 1.5rem;
            }

            /* Responsive Styles */
            @media (max-width: 768px) {
                .navbar {
                    padding: 0 1rem;
                }

                .navbar-left img {
                    height: 40px;
                }

                .profile-name {
                    font-size: 0.9rem;
                }

                .user-icon {
                    font-size: 1.3rem;
                }

                .dropdown-toggle-wrapper {
                    padding: 0.4rem 0.8rem;
                }

                .custom-dropdown-menu {
                    min-width: 130px;
                }

                .dropdown-logout {
                    padding: 0.7rem 0.8rem;
                    font-size: 0.9rem;
                }
            }

            @media (max-width: 480px) {
                .navbar {
                    padding: 0 0.8rem;
                }

                .navbar-left img {
                    height: 35px;
                }

                .profile-name {
                    display: none;
                }

                .dropdown-toggle-wrapper {
                    padding: 0.3rem;
                }

                .user-icon {
                    font-size: 1.2rem;
                }

                .dropdown-icon {
                    display: none;
                }

                .custom-dropdown-menu {
                    right: -0.5rem;
                }

                .content {
                    padding: 1rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="main-content">
            <div class="navbar">
                <div class="navbar-left">
                    <img src="{{ asset('images/logo2.png') }}" alt="CocoaSpace Logo">
                </div>
                <div class="navbar-right">
                    <div class="dropdown-toggle-wrapper" onclick="toggleDropdown()">
                        <span class="profile-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-user-circle user-icon"></i>
                        <i class="fas fa-caret-down dropdown-icon"></i>
                    </div>
                    <div class="custom-dropdown-menu" id="logoutDropdown">
                        <a href="#" class="dropdown-logout" onclick="confirmLogout(event)">
                            Logout
                            <i class="fas fa-sign-out-alt logout-icon"></i>
                        </a>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <div class="content">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            function toggleDropdown() {
                const dropdown = document.getElementById('logoutDropdown');
                dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
                dropdown.classList.toggle('active');
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

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('logoutDropdown');
                const toggle = document.querySelector('.dropdown-toggle-wrapper');
                
                if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                    dropdown.classList.remove('active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const dropdown = document.getElementById('logoutDropdown');
                if (window.innerWidth > 480) {
                    const profileName = document.querySelector('.profile-name');
                    const dropdownIcon = document.querySelector('.dropdown-icon');
                    profileName.style.display = 'block';
                    dropdownIcon.style.display = 'block';
                }
            });
        </script>
    </body>
</html>

