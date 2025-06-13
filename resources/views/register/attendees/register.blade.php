{{-- @extends('register.layouts.app')
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
        margin-top: -150px;
        width: 400px;
        max-width: 100%;
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
        /* box-shadow: 0 2px 8px rgba(9, 9, 9, 0.1); */
        display: flex;
        flex-direction: column;
        align-items: center;
        height:550px;
        margin-top: -100px;
    }
    .register-title {
        color: #42CCC5;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-align: center;
        margin-top: -45px;
        text-transform: uppercase;
    }
    .register-subtext {
        color: #888;
        font-size: 1rem;
        margin-bottom: 50px;
        text-align: center;
        margin-top: 30px;
    }
    .register-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 25px;
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

    @media (max-width: 600px) {
        .header {
            padding: 0 12px;
            height: 54px;
        }
        .header-logo {
            height: 34px;
        }
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
        font-size: 30px;
        text-shadow: 2px 2px 6px rgba(72, 71, 69, 0.7);
        z-index: 10;
        user-select: none;
        text-align: center;
    }
</style>

<style>
    .maximized {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
        background-color: white !important;
        display: flex !important;
        flex-direction: row !important;
        overflow: hidden !important;
    }
</style>

<div class="main-content" id="mainContent">
    <button id="maximizeBtn" title="Maximize" style="position: fixed; top: 10px; right: 10px; z-index: 10000; background-color: #42CCC5; border: none; color: white; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">
        ⬜
    </button>
    <div class="left-illustration" style="position: relative; flex-direction: column; display: flex; align-items: center;">
        <h1 class="system-name">WELCOME TO COCOA BOARD MEETING MANAGEMENT SYSTEM</h1>
        <div class="image-container" style="position: relative; width: 400px; height: 350px; margin-top: 250px; margin-bottom: -150px;">
            @if($bookings->flyer_path)
                <img src="{{ asset($bookings->flyer_path) }}" alt="Programme Flyer" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            @else
                <img src="{{ asset('images/meet.png') }}" alt="Meeting Illustration" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            @endif
        </div>

        <div style="color: white; font-weight: 600; font-size: 18px; text-shadow: 1px 1px 3px rgba(0,0,0,0.7); text-align: center;">
            <h2>{{ $bookings->requester }}</h2>
        </div>
        <div style="color: white; text-align: center; background-color:#42CCC5; padding:7px; border-radius:25px; padding-left:15px; padding-right:15px; letter-spacing:2px; background-color:#fff; color:#1f6d69;">
            <h2 style="font-size: 15px; margin-top:7px; font-weight:bolder;">{{ $bookings->meetingRoom->name}} | {{ $bookings->meetingRoom->room_number }} | {{ $bookings->time}} </h2>
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

<script>
    function applyMaximizeState() {
        const mainContent = document.getElementById('mainContent');
        const navbar = document.querySelector('nav.navbar');
        const btn = document.getElementById('maximizeBtn');
        const isMaximized = localStorage.getItem('isMaximized') === 'true';

        if (isMaximized) {
            mainContent.classList.add('maximized');
            if (navbar) {
                navbar.style.display = 'none';
            }
            btn.textContent = '❐'; // Restore icon
        } else {
            mainContent.classList.remove('maximized');
            if (navbar) {
                navbar.style.display = '';
            }
            btn.textContent = '⬜'; // Maximize icon
        }
    }

    document.getElementById('maximizeBtn').addEventListener('click', function() {
        const mainContent = document.getElementById('mainContent');
        const navbar = document.querySelector('nav.navbar');
        const btn = this;

        if (!mainContent.classList.contains('maximized')) {
            mainContent.classList.add('maximized');
            if (navbar) {
                navbar.style.display = 'none';
            }
            btn.textContent = '❐'; // Restore icon
            localStorage.setItem('isMaximized', 'true');
        } else {
            mainContent.classList.remove('maximized');
            if (navbar) {
                navbar.style.display = '';
            }
            btn.textContent = '⬜'; // Maximize icon
            localStorage.setItem('isMaximized', 'false');
        }
    });

    // Apply maximize state on page load
    document.addEventListener('DOMContentLoaded', function() {
        applyMaximizeState();
    });
</script>
@endsection --}}

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CocoaSpace</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        font-weight: 400;
        line-height: 24px;
        color: rgb(15, 23, 42);
        background: linear-gradient(
          135deg,
          #f0fdfc 0%,
          #ccfbf1 25%,
          #99f6e4 50%,
          #5eead4 75%,
          #2dd4bf 100%
        );
        height: 100vh;
        overflow: hidden;
      }

      .navigation {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        gap: 8px;
      }

      .nav-link {
        display: inline-block;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        text-decoration: none;
        color: #42ccc5;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(66, 204, 197, 0.3);
        font-size: 14px;
        transition: all 0.2s ease;
      }

      .nav-link:hover {
        background: rgba(66, 204, 197, 0.1);
        transform: translateY(-1px);
      }

      .registration-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
      }

      /* Event Section */
      .event-section {
        flex: 1;
        background: linear-gradient(
          135deg,
          #42ccc5 0%,
          #2dd4bf 25%,
          #14b8a6 50%,
          #0f766e 75%,
          #134e4a 100%
        );
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
      }

      .event-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
          radial-gradient(
            circle at 20% 20%,
            rgba(255, 255, 255, 0.1) 0%,
            transparent 50%
          ),
          radial-gradient(
            circle at 80% 80%,
            rgba(255, 255, 255, 0.1) 0%,
            transparent 50%
          );
        opacity: 0.6;
      }

      .event-content {
        position: relative;
        z-index: 1;
        text-align: center;
        max-width: 380px;
        width: 100%;
      }

      .event-title {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 5.3rem;
        letter-spacing: -0.025em;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .event-subtitle {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1rem;
        opacity: 0.9;
      }

      .event-image-container {
        margin: 1rem 0;
        display: flex;
        justify-content: center;
      }

      .event-image {
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        width: 250px;
        height: 300px;
        margin-bottom: 70px;
      }

      .event-details {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin: 1rem 0 2rem;
      }

      .detail-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        font-weight: 500;
      }

      .detail-item:last-child {
        margin-bottom: 0;
      }

      .detail-icon {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        opacity: 0.9;
      }

      /* Form Section */
      .form-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        overflow-y: auto;
      }

      .form-container {
        width: 100%;
        max-width: 460px;
      }

      .form-header {
        text-align: center;
        margin-bottom: 1.5rem;
      }

      .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        background: linear-gradient(135deg, #42ccc5 0%, #0f766e 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding: 20px;
      }

      .form-subtitle {
        color: rgb(71, 85, 105);
        font-size: 1rem;
        font-weight: 500;
      }

      .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow:
          0 20px 40px rgba(66, 204, 197, 0.15),
          0 8px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(66, 204, 197, 0.2);
      }

      .success-message {
        background: linear-gradient(135deg, #42ccc5 0%, #14b8a6 100%);
        color: white;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        display: none;
      }

      .registration-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .form-row {
        display: flex;
        gap: 1rem;
      }

      .form-group {
        flex: 1;
      }

      .form-label {
        display: block;
        color: rgb(30, 41, 59);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
      }

      .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(203, 213, 225, 0.8);
        border-radius: 10px;
        font-size: 0.9rem;
        font-family: inherit;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.9);
      }

      .form-input:focus {
        border-color: #42ccc5;
        box-shadow: 0 0 0 3px rgba(66, 204, 197, 0.1);
        transform: translateY(-1px);
        background: white;
      }

      .form-input::placeholder {
        color: rgb(156, 163, 175);
        font-weight: 400;
      }

      .submit-button {
        width: 100%;
        background: linear-gradient(135deg, #42ccc5 0%, #14b8a6 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.5rem;
      }

      .submit-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(66, 204, 197, 0.4);
      }

      .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
      }

      .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s linear infinite;
        margin-right: 8px;
      }

      @keyframes spin {
        to {
          transform: rotate(360deg);
        }
      }

      /* Large Desktop (1440px+) */
      @media (min-width: 1440px) {
        .registration-container {
          max-width: 1600px;
          margin: 0 auto;
        }

        .event-content {
          max-width: 450px;
        }

        .form-container {
          max-width: 520px;
        }

        .event-title {
          font-size: 2.5rem;
        }

        .form-title {
          font-size: 2rem;
        }
      }

      /* Standard Desktop (1200px - 1439px) */
      @media (max-width: 1439px) and (min-width: 1200px) {
        .event-section {
          padding: 2rem;
        }

        .form-section {
          padding: 2rem;
        }
      }

      /* Small Desktop / Large Tablet (1024px - 1199px) */
      @media (max-width: 1199px) and (min-width: 1024px) {
        .registration-container {
          height: auto;
          min-height: 100vh;
        }

        .event-section {
          padding: 1.5rem;
        }

        .form-section {
          padding: 1.5rem;
          overflow-y: auto;
        }

        .event-title {
          font-size: 2rem;
        }

        .form-title {
          font-size: 1.7rem;
        }

        .event-details {
          margin: 1rem 0 1.5rem;
        }
      }

      /* Tablet Landscape (768px - 1023px) - Keep desktop-style layout */
      @media (max-width: 1023px) and (min-width: 768px) and (orientation: landscape) {
        .registration-container {
          flex-direction: row;
          height: 100vh;
          overflow: hidden;
        }

        .event-section {
          flex: 1;
          padding: 1.2rem;
        }

        .form-section {
          flex: 1;
          padding: 1.2rem;
          overflow-y: auto;
        }

        .event-title {
          font-size: 1.8rem;
          margin-bottom: 0.3rem;
        }

        .event-subtitle {
          font-size: 0.95rem;
          margin-bottom: 1rem;
        }

        .form-title {
          font-size: 1.5rem;
        }

        .form-card {
          padding: 1.5rem;
        }

        .event-image {
          width: 160px;
        }

        .event-details {
          padding: 1.2rem;
          margin: 1rem 0 1rem;
        }

        .detail-item {
          font-size: 0.8rem;
          margin-bottom: 0.8rem;
        }

        .detail-icon {
          width: 18px;
          height: 18px;
          margin-right: 10px;
        }

        .registration-form {
          gap: 0.9rem;
        }

        .form-input {
          padding: 0.7rem 0.9rem;
          font-size: 0.85rem;
        }
      }

      /* Tablet Portrait (768px - 1023px) - Stack layout */
      @media (max-width: 1023px) and (min-width: 768px) and (orientation: portrait) {
        .registration-container {
          flex-direction: column;
          height: auto;
          overflow: visible;
        }

        .event-section {
          min-height: 45vh;
          padding: 1.5rem;
        }

        .form-section {
          padding: 1.5rem;
          overflow-y: visible;
        }

        .event-title {
          font-size: 1.9rem;
          margin-bottom: 0.4rem;
        }

        .event-subtitle {
          font-size: 1rem;
          margin-bottom: 1.2rem;
        }

        .form-title {
          font-size: 1.6rem;
        }

        .form-card {
          padding: 1.75rem;
        }

        .event-image {
          width: 180px;
        }

        .event-details {
          padding: 1.3rem;
          margin: 1.2rem 0 1rem;
        }

        .detail-item {
          font-size: 0.85rem;
          margin-bottom: 0.9rem;
        }
      }

      /* General Tablet (768px - 1023px) - Fallback for devices that don't support orientation queries */
      @media (max-width: 1023px) and (min-width: 768px) {
        .event-content {
          max-width: 350px;
        }

        .form-container {
          max-width: 420px;
        }

        .detail-icon {
          width: 18px;
          height: 18px;
          margin-right: 10px;
        }
      }

      /* Mobile Landscape / Small Tablet (641px - 767px) */
      @media (max-width: 767px) and (min-width: 641px) {
        .registration-container {
          flex-direction: column;
          height: auto;
          overflow: visible;
        }

        .event-section {
          min-height: 42vh;
          padding: 1.25rem;
        }

        .form-section {
          padding: 1.25rem;
        }

        .event-title {
          font-size: 1.7rem;
          margin-bottom: 0.3rem;
        }

        .event-subtitle {
          font-size: 0.95rem;
          margin-bottom: 1rem;
        }

        .form-title {
          font-size: 1.5rem;
        }

        .form-card {
          padding: 1.5rem;
        }

        .form-row {
          flex-direction: column;
          gap: 1rem;
        }

        .event-image {
          width: 160px;
        }

        .event-details {
          padding: 1.2rem;
          margin: 1rem 0 0.8rem;
        }

        .detail-item {
          font-size: 0.8rem;
          margin-bottom: 0.8rem;
        }

        .detail-icon {
          width: 18px;
          height: 18px;
          margin-right: 10px;
        }
      }

      /* Mobile Portrait (481px - 640px) */
      @media (max-width: 640px) and (min-width: 481px) {
        .registration-container {
          flex-direction: column;
          height: auto;
          overflow: visible;
        }

        .event-section {
          min-height: 38vh;
          padding: 1rem;
        }

        .form-section {
          padding: 1rem;
        }

        .event-title {
          font-size: 1.6rem;
          margin-bottom: 0.25rem;
        }

        .event-subtitle {
          font-size: 0.9rem;
          margin-bottom: 0.9rem;
        }

        .form-title {
          font-size: 1.4rem;
        }

        .form-card {
          padding: 1.25rem;
        }

        .form-row {
          flex-direction: column;
          gap: 1rem;
        }

        .registration-form {
          gap: 0.9rem;
        }

        .event-image {
          width: 140px;
        }

        .event-details {
          padding: 1rem;
          margin: 0.9rem 0 0.6rem;
        }

        .detail-item {
          font-size: 0.75rem;
          margin-bottom: 0.7rem;
        }

        .detail-icon {
          width: 16px;
          height: 16px;
          margin-right: 8px;
        }

        .form-input {
          padding: 0.7rem 0.9rem;
          font-size: 0.85rem;
        }

        .submit-button {
          padding: 0.9rem 1.5rem;
          font-size: 0.95rem;
        }
      }

      /* Small Mobile (320px - 480px) */
      @media (max-width: 480px) {
        .registration-container {
          flex-direction: column;
          height: auto;
          overflow: visible;
        }

        .event-section {
          min-height: 35vh;
          padding: 0.75rem;
        }

        .form-section {
          padding: 0.75rem;
        }

        .event-title {
          font-size: 1.4rem;
          margin-bottom: 0.2rem;
        }

        .event-subtitle {
          font-size: 0.85rem;
          margin-bottom: 0.8rem;
        }

        .form-header {
          margin-bottom: 1.2rem;
        }

        .form-title {
          font-size: 1.25rem;
          margin-bottom: 0.2rem;
        }

        .form-subtitle {
          font-size: 0.9rem;
        }

        .form-card {
          padding: 1rem;
          border-radius: 12px;
        }

        .form-row {
          flex-direction: column;
          gap: 0.9rem;
        }

        .registration-form {
          gap: 0.8rem;
        }

        .event-image {
          width: 120px;
        }

        .event-details {
          padding: 0.9rem;
          margin: 0.8rem 0 0.5rem;
          border-radius: 12px;
        }

        .detail-item {
          font-size: 0.7rem;
          margin-bottom: 0.6rem;
        }

        .detail-icon {
          width: 14px;
          height: 14px;
          margin-right: 6px;
        }

        .form-label {
          font-size: 0.8rem;
          margin-bottom: 0.4rem;
        }

        .form-input {
          padding: 0.6rem 0.8rem;
          font-size: 0.8rem;
          border-radius: 8px;
        }

        .submit-button {
          padding: 0.8rem 1.2rem;
          font-size: 0.9rem;
          border-radius: 10px;
        }

        .success-message {
          padding: 0.8rem;
          font-size: 0.8rem;
          border-radius: 10px;
        }
      }

      /* Extra Small Mobile (max-width: 319px) */
      @media (max-width: 319px) {
        .event-section {
          padding: 0.5rem;
          min-height: 32vh;
        }

        .form-section {
          padding: 0.5rem;
        }

        .event-title {
          font-size: 1.2rem;
        }

        .event-subtitle {
          font-size: 0.8rem;
        }

        .form-title {
          font-size: 1.1rem;
        }

        .form-card {
          padding: 0.8rem;
        }

        .event-image {
          width: 100px;
        }

        .event-details {
          padding: 0.7rem;
        }

        .detail-item {
          font-size: 0.65rem;
        }

        .form-input {
          padding: 0.5rem 0.7rem;
          font-size: 0.75rem;
        }

        .submit-button {
          padding: 0.7rem 1rem;
          font-size: 0.85rem;
        }
      }
    </style>
  </head>
  <body>
    <nav class="navigation">
      <a href="{{ route('register.attendees.index') }}" class="nav-link">← Home</a>
      {{-- <a href="/login.html" class="nav-link">Login</a> --}}
    </nav>

    <div class="registration-container">
      <!-- Event Details Section -->
      <div class="event-section">
        <div class="event-content">
          <h1 class="event-title">{{ $bookings->requester }}</h1>
          {{-- <p class="event-subtitle">Innovation Meets Opportunity</p> --}}

          <div class="event-image-container">
            @if($bookings->flyer_path)
                <img src="{{ asset($bookings->flyer_path) }}" alt="Event logo" class="event-image" loading="lazy">
            @else
                <img src="{{ asset('images/meet.png') }}" alt="Meeting Illustration" class="event-image" loading="lazy">
            @endif
          </div>

          <div class="event-details">
            <div class="detail-item">
              <svg class="detail-icon" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                />
              </svg>
              <span>{{ $bookings->meetingRoom->name}}</span>
            </div>
              <div class="detail-item">
                <svg class="detail-icon" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 19V5c0-1.1-.9-2-2-2H7c-1.1 0-2 .9-2 2v14h2v-7h2v7h10zM15 11h-2V9h2v2z"/>
                </svg>
              <span>Room {{ $bookings->meetingRoom->room_number }}</span>
            </div>
            <div class="detail-item">
              <svg class="detail-icon" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"
                />
              </svg>
              <span>{{ $bookings->date }}</span>
            </div>
            <div class="detail-item">
              <svg class="detail-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2zm1 11h-4V7h2v4h2z"/>
              </svg>
              <span>{{ $bookings->time }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Registration Form Section -->
      <div class="form-section">
        <div class="form-container">
          <div class="form-header">
            <h2 class="form-title">Registration Form</h2>
            <p class="form-subtitle">Join us for an unforgettable experience</p>
          </div>

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

            <form class="registration-form" action="{{ route('register.attendees.store') }}" method="POST" id="registerForm">
            @csrf
                <input type="hidden" name="booking_id" value="{{ $bookings->id }}">
                <input type="hidden" name="registration_time" id="registration_time" value="">

                <div class="form-row">
                <div class="form-group">
                  <label for="firstName" class="form-label">First Name *</label>
                  <input
                    type="text"
                    name="name"
                    required
                    placeholder="Enter full name"
                    class="form-input"
                  />
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Email Address *</label>
                <input
                  type="email"
                  name="email"
                  required
                  placeholder="Enter email address"
                  class="form-input"
                />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="phone" class="form-label">Phone Number *</label>
                  <input
                    type="tel"
                    name="phone"
                    required
                    placeholder="0244444444"
                    class="form-input"
                  />
                </div>
                <div class="form-group">
                  <label for="gender" class="form-label">Gender *</label>
                  <select  name="gender" class="form-input" required>
                    <option value="" disabled selected>Select gender</option>
                    <option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
                    <option value="Female" @if(old('gender') == 'Female') selected @endif>Female</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="department" class="form-label"
                  >Company/Organization/Department *</label
                >
                <input
                  type="text"
                  name="department"
                  required
                  placeholder="Enter company, organization or department"
                  class="form-input"
                />
              </div>

              <button type="submit" class="submit-button">
                Register Now
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script>
    document.getElementById('registerForm').addEventListener('submit', function() {
      const now = new Date();
      const isoString = now.toISOString();
      document.getElementById('registration_time').value = isoString;
    });
  </script>
</html>
