<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>CocoaSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary-color: #42ccc5;
            --hover-color: #FFFFFF;
            --text-color: #333333;
            --sidebar-width: 220px;
            --header-height: 80px;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--primary-color);
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Header Styles */
        .head {
            background-color: var(--primary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo {
            width: 15rem;
        }

        .logo img {
            width: 100%;
            height: auto;
            max-width: 200px;
            transition: all 0.3s ease;
        }

        .admin {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            font-size: 20px;
            color: var(--hover-color);
            font-weight: bold;
            margin-top:10px;
        }

        .admin-logo {
            width: 3rem;
            margin-top:-15px;
        }

        .admin-logo img {
            width: 100%;
            height: auto;
            border-radius: 50%;
            border: 2px solid var(--hover-color);
        }

        /* Content Layout */
        .content {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
            padding-top: var(--header-height);
        }

        /* Main Content */
        .main {
            padding: 2rem;
            background-color: var(--hover-color);
            border-top-left-radius: 2rem;
            min-height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            position: fixed;
            top: var(--header-height);
            left: 0;
            background-color: var(--primary-color);
            transition: all 0.3s ease;
            z-index: 999;
        }

        .options {
            padding: 1rem 0;
            list-style: none;
            margin-top: 2rem;
        }

        .options li {
            padding: 0.8rem 1.5rem;
            margin: 0.5rem 0;
            transition: all 0.3s ease;
            border-radius: 2rem 0rem 0rem 2rem;
        }

        .options li a {
            text-decoration: none;
            color: var(--hover-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1rem;
        }

        .options li.active {
            background-color: var(--hover-color);
        }

        .options li.active a {
            color: var(--primary-color);
        }

        .options li:hover {
            background-color: var(--hover-color);
        }

        .options li:hover a {
            color: var(--primary-color);
        }

        .sign-out {
            margin-top: auto;
            padding: 1rem 0;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--hover-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .content {
                grid-template-columns: 1fr;
            }

            .main {
                margin-left: 0;
                border-radius: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .overlay.active {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .head {
                padding: 0.5rem;
            }

            .logo img {
                max-width: 150px;
            }

            .admin {
                font-size: 16px;
            }

            .admin-logo {
                width: 2.5rem;
            }

            .main {
                padding: 1rem;
            }

            .options li {
                padding: 0.6rem 1rem;
            }

            .options li a {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .head {
                height: 60px;
            }

            .logo img {
                max-width: 120px;
            }

            .admin {
                font-size: 14px;
                gap: 0.5rem;
            }

            .admin-logo {
                width: 2rem;
            }

            .main {
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="head">
            <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
            <div class="logo">
                <img src="{{ asset('images/logo2.png') }}" alt="CocoaSpace Logo">
            </div>
            <div class="admin">
                <p>{{ Auth::user()->name }}</p>
                <div class="admin-logo">
                    <img src="{{ asset('images/111.jpg') }}" alt="Admin Profile">
                </div>
            </div>
        </div>
    </header>

    <div class="overlay" onclick="toggleSidebar()"></div>

    <div class="content">
        <nav>
            <div class="sidebar">
                <ul class="options">
                    <li class="{{ request()->is('admin/home') ? 'active' : '' }}">
                        <a href="{{ route('admin.home')}}">
                            <i class="fa-solid fa-gauge"></i> Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/room') ? 'active' : '' }}">
                        <a href="{{ route('admin.room')}}">
                            <i class="fa-solid fa-door-open"></i> Meeting Rooms
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/bookings') ? 'active' : '' }}">
                        <a href="{{ route('admin.bookings')}}">
                            <i class="fa-solid fa-calendar-days"></i> Bookings
                        </a>
                    </li>
                    <li class="{{ request()->is('attendees') ? 'active' : '' }}">
                        <a href="{{ route('admin.registration')}}">
                            <i class="fa-solid fa-address-card"></i> Registration
                        </a>
                    </li>
                    {{-- <li class="{{ request()->is('requests') ? 'active' : '' }}">
                        <a href="{{ route('admin.requests')}}">
                            <i class="fa-solid fa-list"></i> Requests
                        </a>
                    </li> --}}
                    <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard')}}">
                            <i class="fa-solid fa-users"></i> Users
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/bookings/history') ? 'active' : '' }}">
                        <a href="{{ route('admin.bookings.history')}}">
                            <i class="fas fa-door-closed"></i> History
                        </a>
                    </li>
                    <li>
                        <a href="#" id="openSidebarBookModal">
                            <i class="fa-solid fa-plus"></i> Book Meeting Room
                        </a>
                    </li>
                    <li>
                        <a href="#" id="openSidebarAttachModal">
                            <i class="fa-solid fa-paperclip"></i> Attach Document
                        </a>
                    </li>
                    <li class="sign-out">
                        <a href="{{ route('logout') }}" onclick="confirmLogout(event)">
                            <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="main">
            @yield('content')
        </main>
    </div>

    <div id="sidebarBookMeetingModal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
        <div class="modal-content" style="background:#fff; border-radius:12px; padding:32px; max-width:420px; margin:auto; position:relative;">
            <span class="close" id="closeSidebarBookModal" style="position:absolute; top:12px; right:18px; font-size:28px; cursor:pointer;">&times;</span>
            <h2 style="margin-bottom:18px;">Book a Meeting Room</h2>
            @include('admin.partials.booking_form', ['rooms' => $rooms])
        </div>
    </div>

    <div id="sidebarAttachDocumentModal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
        <div class="modal-content" style="background:#fff; border-radius:12px; padding:32px; max-width:480px; margin:auto; position:relative; min-width:320px;">
            <span class="close" id="closeSidebarAttachModal" style="position:absolute; top:12px; right:18px; font-size:28px; cursor:pointer;">&times;</span>
            <h2 style="margin-bottom:18px;">Attach Document to Booking</h2>
            <div id="attachDocumentFormContainer">
                <!-- The form wil be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
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

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const overlay = document.querySelector('.overlay');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(event.target) && 
                !mobileMenuBtn.contains(event.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('sidebarBookMeetingModal');
            var openBtn = document.getElementById('openSidebarBookModal');
            var closeBtn = document.getElementById('closeSidebarBookModal');
            if (openBtn && modal && closeBtn) {
                openBtn.onclick = function(e) {
                    e.preventDefault();
                    modal.style.display = 'flex';
                };
                closeBtn.onclick = function() {
                    modal.style.display = 'none';
                };
            }
        });

        // Sidebar Attach Document Modal logic
        document.addEventListener('DOMContentLoaded', function () {
            var attachModal = document.getElementById('sidebarAttachDocumentModal');
            var openAttachBtn = document.getElementById('openSidebarAttachModal');
            var closeAttachBtn = document.getElementById('closeSidebarAttachModal');
            var formContainer = document.getElementById('attachDocumentFormContainer');
            if (openAttachBtn && attachModal && closeAttachBtn && formContainer) {
                openAttachBtn.onclick = function(e) {
                    e.preventDefault();
                    // Load the form via AJAX
                    fetch("{{ route('admin.attach-document') }}", {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.text())
                        .then(html => {
                            formContainer.innerHTML = html;
                            attachModal.style.display = 'flex';
                        });
                };
                closeAttachBtn.onclick = function() {
                    attachModal.style.display = 'none';
                };
            }
        });
    </script>
</body>
</html>
