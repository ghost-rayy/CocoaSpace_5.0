@extends('register.layouts.app')
@section('content')

<style>
    .container-flex {
        display: flex;
        justify-content: center;
        gap: 60px;
        margin-top: 40px;
        margin-bottom: 40px;
    }
    .left-section {
        text-align: center;
        max-width: 400px;
        color: #999;
    }
    .left-section img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        max-height:70%;
    }
    .meeting-title {
        font-weight: 600;
        font-size: 18px;
        color: #666;
        margin-top: 15px;
        margin-bottom: 10px;
    }
    .meeting-details {
        background-color: #b7d9d9;
        border-radius: 20px;
        padding: 8px 20px;
        font-size: 14px;
        color: #2ca6a4;
        display: flex;
        justify-content: space-around;
        margin-top: 10px;
    }
    .right-section {
        background-color: #d9f0f0;
        padding: 30px 40px;
        border-radius: 15px;
        max-width: 400px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        height:100%;
    }
    .form-title {
        font-weight: 700;
        font-size: 22px;
        color: #2ca6a4;
        margin-bottom: 10px;
        text-align: center;
    }
    .form-subtitle {
        font-size: 14px;
        color: #555;
        margin-bottom: 25px;
        text-align: center;
    }
    form input[type="text"],
    form input[type="email"],
    form input[type="tel"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }
    .gender-group {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #333;
    }
    .gender-group label {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .verify-link {
        margin-left: auto;
        font-size: 13px;
        color: #2ca6a4;
        cursor: pointer;
        text-decoration: underline;
    }
    .btn-register {
        width: 100%;
        background-color: #2ca6a4;
        color: white;
        padding: 12px 0;
        border: none;
        border-radius: 25px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-register:hover {
        background-color: #23807e;
    }
</style>

<div class="container-flex">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="left-section">
        @if($bookings->flyer_path)
            <img src="{{ asset($bookings->flyer_path) }}" alt="Programme Flyer">
        @else
            <img src="{{ asset('images/meet.png') }}" alt="Meeting Illustration">
        @endif
        <div class="meeting-title">Planning Committee Meeting</div>
        <div class="meeting-details">
            <span>Board Room</span>
            <span>9:00 AM</span>
            <span>24th April 2025</span>
        </div>
    </div>

    <div class="right-section">
        <div class="form-title">Registration Form</div>
        <div class="form-subtitle">Please fill this registration form to register you for the meeting</div>

        <form action="{{ route('register.attendees.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $bookings->id }}">

            <input type="text" name="name" placeholder="Full Name" required>

            <div class="gender-group">
                <label><input type="radio" name="gender" value="Male" required> Male</label>
                <label><input type="radio" name="gender" value="Female" required> Female</label>
            </div>

            <input type="email" name="email" placeholder="Email" required>

            <input type="text" name="department" placeholder="Department/ Company :" required>

            <input type="tel" name="phone" placeholder="Phone Number" required>

            <button type="submit" class="btn-register">Register</button>
        </form>
    </div>
</div>

@endsection
