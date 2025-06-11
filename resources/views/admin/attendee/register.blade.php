@extends('layouts.admin-sidebar')

@section('content')

@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: true,
        confirmButtonText: 'Done',
        confirmButtonColor: '#42CCC5',
        background: '#fff',
        iconColor: '#42CCC5'
    }).then((result) => {
        if (result.isConfirmed) {
            // Removed redirect to keep on the same page
            // window.location.href = "{{ route('admin.registration') }}";
        }
    });
</script>
@endif

<div class="registration-container">
    <div class="meeting-details">
        <h1>Welcome to COCOASPACE</h1>

        <div class="meeting-image-container">
            <img src="{{ asset('images/meet.png') }}" alt="Meeting Illustration" class="meeting-image">
        </div>

        <h2>TITLE: {{ $bookings->requester }}</h2>

        <table class="meeting-table">
            <tr>
                <td>{{ $bookings->meetingRoom->name }}</td>
                <td>{{ $bookings->time}}</td>
                <td>{{ $bookings->date }}</td>
            </tr>
        </table>
    </div>

    <div class="form-container"><br>
        <a href="{{ route('admin.registration') }}" class="bn">← Back</a>
        <h2 class="form-title">Registration Form</h2>
        <p class="form-subtitle">Please fill this registration form to register yourdelf or someone for the meeting</p>

        <form action="{{ route('admin.attendees.store') }}" method="POST" class="form-content">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $bookings->id }}">

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group gender-group">
                <label>Gender:</label>
                <label><input type="radio" name="gender" value="Male" required> Male</label>
                <label><input type="radio" name="gender" value="Female" required> Female</label>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="department">Department/ Company :</label>
                <input type="text" name="department" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" name="phone"  required>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn-submit">Register</button>
                {{-- <a class="link" href="#">Verify if already registered</a> --}}
            </div>
        </form>
    </div>
</div>

@endsection

<style>
    .meeting-image-container {
        margin: 20px 0;
        text-align: center;
    }

    .meeting-image {
        max-width: 300%;
        height: 80%;
        max-height: 500%;
        border-radius: 8px;
    }
    .registration-container {
        display: flex;
        gap: 30px;
        padding: 15px;
        height: 90%;
    }

    .meeting-details {
        width: 40%;
        padding: 30px;
        border-radius: 12px;
    }

    .meeting-details h1 {
        font-size: 30px;
        font-weight: bold;
        color: #103754;
        margin-bottom: 15px;
        text-transform: uppercase;
        text-align: center;
    }

    .meeting-details h2 {
        font-size: 20px;
        color: #4e5a63;
        margin: 15px 0 10px;
        text-align: center;
        font-weight: bolder;
        text-transform: uppercase;
    }

    .meeting-details h3 {
        font-size: 16px;
        margin: 15px 0 5px;
    }

    .registration-info {
        list-style-type: none;
        padding-left: 0;
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }

    .meeting-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .meeting-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
        background-color: white;
    }

    .form-container {
        background: #E6F5FD;
        padding: 10px;
        border-radius: 12px;
        width: 40%;
        height: 110%;
        box-shadow: 0 0 0 2px #0b7dda20;
        margin-left: 100px;
        padding: 0px 30px 0px;
    }

    a {
        font-weight: bolder;
    }

    .form-title {
        font-size: 26px;
        font-weight: bold;
        color: #103754;
        margin-bottom: 5px;
    }

    .form-subtitle {
        color: #666;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .form-content {
        text-align: left;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-size: 12px;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .gender-group {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .gender-group input {
        margin-right: 5px;
    }

    .gender-group a {
        margin-left: auto;
        color: #42CCC5;
        font-weight: bold;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-submit {
        background-color: #1CB5A3;
        border: none;
        padding: 12px 35px;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        width: 300px;
        margin-bottom: 10px;
    }

    .btn-submit:hover {
        background-color: #17a593;
    }

    .footer-details {
        margin-top: 35px;
    }

    .footer-details h4 {
        margin-bottom: 5px;
        color: #103754;
        font-size: 16px;
        font-weight: 600;
    }

    .footer-boxes {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .box {
        background: #B8EDE9;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        color: #103754;
        text-align: center;
        min-width: 100px;
    }
    .bn{
        background-color: none;
        border-radius: 20px;
        padding: 10px;
        text-decoration: none;
        color: #000000;
        font-size: 15px;
    }
    .bn:hover{
        text-decoration: underline;
        color: rgb(255, 0, 0);
}
</style>
