<nav class="navbar navbar-expand-md ">
    <style>
        /* Custom styles for header */
        .navbar {
            background-color: #42CCC5;
            padding: 10px 20px;
            margin-top: -35px;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
            color: #f6f6f6;
        }
        .nav-link {
            color: #333;
        }
        .nav-link:hover {
            color: #18403e;
        }
        .dropdown-menu {
            min-width: 150px;
        }
    </style>
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('register.attendees.index') }}">
            <img src="/images/logo.png" alt="CocoaSpace Logo" style="height: 40px; margin-right: 10px;">
            
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a style="color: white;" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
