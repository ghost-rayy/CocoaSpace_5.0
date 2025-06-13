<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>CocoaSpace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ENjdO4Dr2bkBIFxQpeoYz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">

    <style>
        *{
            padding: 0;
            margin: 0;
        }
        .head{
            background-color: #42ccc5;
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            height: 20px;
        }
        body{
            background-color: #42ccc5;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            margin-top: -65px;
        }
        /* HEADER CONTENTS */
        .logo{
            width: 5rem;
        }
        .logo img {
            width: 300%;
            height: 500%;
            margin-top: 50px;
        }
        .admin {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 90px;
            font-size: 20px;
            color: #ffffff;
            font-weight: bolder;
        }
        .admin-logo{
            width: 3rem;
        }
        .admin-logo img {
            width: 100%;
            height: auto;
            border-radius: 50%;
        }

        /* CONTENT CONTAINING THE SIDEBAR AND THE MAIN CONTENT*/

        .content {
            display: grid;
            grid-template-columns: minmax(200px, auto) 1fr;
        }


        /* MAIN */
        .main {
            padding: 2.5rem;
            border-top-left-radius: 4rem;
            background-color: #FFFFFF;
            margin-top:105px;
            overflow-y: auto;
            width: 1250px;
        }
        .main {
            height: 77.9vh;
        }
        .main h2 {
            text-align: center;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            margin-left: -20px;
            height: 82vh;
            display: flex;
            flex-direction: column;
            justify-content:space-evenly;
            position: sticky;
            margin-top: 150px;
        }

        .sidebar li.active {
            background-color: #FFFFFF;
            border-radius-left: 50px;
        }

        .options {
            padding: 2rem;
            padding-right: 0;
            /* border: 1px solid red; */
            margin-bottom: 12rem;
        }


        .options li {
            padding: 1rem;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: 0.3s ease-in;
            border-bottom-left-radius: 2rem;
            border-top-left-radius: 2rem;
            /* border: 1px solid red; */
        }

        .options li a {
            text-decoration: none;
            color: rgb(255, 255, 255);
        }


        .options li:last-child {
        margin-top: 8rem;
        }

        .sign-out {
            padding: 2rem;
            padding-right: 0;
            margin-top: 8rem;
        }

        .options li:hover {
            background-color: #FFFFFF;
        }

        .options li:hover a {
            color: #42ccc5;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <style>
        /* Responsive styles */
        @media (max-width: 1024px) {
            .content {
                display: grid;
                grid-template-columns: 80px 1fr;
                grid-template-rows: auto;
                grid-template-areas: "sidebar main";
                min-height: 100vh;
            }
            .sidebar {
                grid-area: sidebar;
                width: 190px;
                height: 100vh;
                position: sticky;
                top: 0;
                margin-top: 0;
                display: flex;
                flex-direction: column;
                overflow-y: auto;
                white-space: normal;
            }
            .sidebar ul.options {
                display: flex;
                flex-direction: column;
                padding: 0;
                margin: 0;
                width: 100%;
            }
            .sidebar ul.options li {
                margin: 0;
                border-radius: 0;
                border-bottom-left-radius: 0;
                border-top-left-radius: 0;
            }
            .main {
                grid-area: main;
                width: auto;
                margin-top: 100;
                height: auto !important;
                min-height: auto !important;
                max-height: auto;
                padding: 1rem;
                margin-left: 50px;
            }
        }
        @media (max-width: 600px) {
            .sidebar ul.options li a {
                font-size: 14px;
                padding: 0.5rem 0.75rem;
            }
            .admin {
                font-size: 16px;
                margin-top: 1rem;
            }
            .head {
                flex-direction: column;
                height: auto;
                padding: 1rem 0.5rem;
            }
            .logo img {
                width: 150%;
                height: auto;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="head">
            <div class="logo">
                <img src="{{ asset('images/logo2.png') }}" alt="">
            </div>
            <div class="admin">
                <p> {{ Auth::user()->name }}</p>
                <div class="admin-logo">
                    <img src="{{ asset('images/111.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </header>

    <div class="content">
        <nav>
            <div class="sidebar">
                <ul class="options">
                    <li class="{{ request()->is('admin/home') ? 'active' : '' }}"><a href="{{ route('admin.home')}}"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                    <li class="{{ request()->is('admin/room') ? 'active' : '' }}"><a href="{{ route('admin.room')}}"><i class="fa-solid fa-door-open"></i> Meeting Rooms</a></li>
                    <li class="{{ request()->is('admin/bookings') ? 'active' : '' }}"><a href="{{ route('admin.bookings')}}"><i class="fa-solid fa-calendar-days"></i> Bookings</a></li>
                    <li class="{{ request()->is('attendees') ? 'active' : '' }}"><a href="{{ route('admin.registration')}}"><i class="fa-solid fa-address-card"></i> Registration</a></li>
                    <li class="{{ request()->is('requests') ? 'active' : '' }}"><a href="{{ route('admin.requests')}}"><i class="fa-solid fa-list"></i> Requests</a></li>
                    <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard')}}"><i class="fa-solid fa-users"></i> Users</a></li>
                    <li class="{{ request()->is('admin/bookings/history') ? 'active' : '' }}"><a href="{{ route('admin.bookings.history')}}"><i class="fas fa-door-closed"></i> History</a></li><br><br><br>
                    <li><a href="{{ route('logout') }}" onclick="confirmLogout(event)"><i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }} </a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
            </div>
        </nav>
        <main>
            <div class="main">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>

<script>
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
</script>
