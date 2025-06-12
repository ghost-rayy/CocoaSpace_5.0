@extends('register.layouts.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }
    body, html {
        height: 100%;
        width: 100%;
        background: #f8f9fa;
        overflow: hidden;
    }
    .main-content {
        display: flex;
        height: 85vh;
        overflow: hidden;
    }
    .left-illustration {
        flex: 1.2;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #42CCC5;
        border-radius: 15px 0 0 15px;
        margin-left: 20px;
    }
    .left-illustration img {
        margin-top: -100px;
        width: 400px;
        max-width: 90vw;
        border-radius: 15px;
        /* box-shadow: 0 4px 15px rgba(0,0,0,0.1); */
    }
    .form-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 0 15px 15px 0;
        box-shadow: 0 8px 32px rgba(66,204,197,0.10);
        height: 100%;
        overflow-y: auto;
        padding: 40px 0;
        margin-right: 20px;
        margin-top: 0px;
    }
    .register-card {
        width: 100%;
        max-width: 550px;
        padding: 40px 32px 32px 32px;
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(9, 9, 9, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .register-title {
        color: #42CCC5;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-align: center;
        margin-top: -45px;
    }
    .register-subtext {
        color: #888;
        font-size: 1rem;
        margin-bottom: 28px;
        text-align: center;
    }
    .register-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .register-input, .register-form select {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        background: #fafbfc;
        color: #222;
        outline: none;
        transition: border 0.2s;
    }
    .register-input:focus {
        border: 1.5px solid #42CCC5;
        background: #fff;
    }
    .register-btn {
        width: 100%;
        background: #42CCC5;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 14px 0;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 8px;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(66,204,197,0.10);
    }
    .register-btn:hover {
        background: #1f6d69;
    }
    .login-link {
        color: #42CCC5;
        font-size: 0.95rem;
        text-align: center;
        text-decoration: none;
        margin-top: 18px;
        display: block;
        transition: color 0.2s;
    }
    .login-link:hover {
        color: #1f6d69;
    }
    @media (max-width: 900px) {
        .main-content {
            flex-direction: column;
            height: auto;
            margin-top: 64px;
        }
        .left-illustration {
            display: none;
        }
        .form-section {
            border-radius: 0;
            box-shadow: none;
            height: calc(100vh - 64px);
            padding: 24px 0;
            margin: 0 10px;
        }
    }
    @media (max-width: 600px) {
        .main-content {
            margin-top: 54px;
        }
        .register-card {
            padding: 24px 4px 16px 4px;
            border-radius: 12px;
        }
    }
    .system-name {
        position: absolute;
        top: 20px;
        left: 20px;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
        z-index: 10;
        user-select: none;
        text-align: center;
    }
</style>

<div class="main-content">
    <div class="left-illustration" style="position: relative; flex-direction: column; display: flex; align-items: center;">
        <h1 class="system-name">WELCOME TO COCOA BOARD MEETING MANAGEMENT SYSTEM</h1>
        <div class="image-container" style="position: relative; width: 400px; height: 250px; margin-top: 130px; margin-bottom: 0px;">
            @if($bookings->flyer_path)
                <img src="{{ asset($bookings->flyer_path) }}" alt="Programme Flyer" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            @else
                <img src="{{ asset('images/meet.png') }}" alt="Meeting Illustration" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            @endif
        </div>

        <div style="color: white; font-weight: 600; font-size: 18px; text-shadow: 1px 1px 3px rgba(0,0,0,0.7); text-align: center;">
            <h2>{{ $bookings->requester }}</h2>
        </div>
        <div style="color: white; text-align: center; background-color:#42CCC5; padding:5px; border-radius:15px;">
            <h2 style="font-size: 15px;">{{ $bookings->meetingRoom->name}} | {{ $bookings->time}}</h2>
        </div>
    </div>
    <div class="form-section">
        <div class="register-card">
            <div class="register-title">Registration</div>
            <div class="register-subtext">Please fill this registration form to register for the meeting</div>

            @if(session('success'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
            @endif

            <form class="register-form" action="{{ route('register.attendees.store') }}" method="POST" id="registerForm">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $bookings->id }}">

                <input type="text" class="register-input" name="name" placeholder="Full Name" required>

                <select class="register-input" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
                    <option value="Female" @if(old('gender') == 'Female') selected @endif>Female</option>
                </select>

                <input type="email" class="register-input" name="email" placeholder="Email" required>

                <input type="text" class="register-input" name="department" placeholder="Department/ Company" required>

                <input type="tel" class="register-input" name="phone" placeholder="Phone Number" required>

                <button type="submit" class="register-btn">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection
