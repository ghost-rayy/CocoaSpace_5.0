@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')

<style>
    body {
        background-color: #f4faff;
        font-family: 'Segoe UI', sans-serif;
    }

    .container {
        background-color: white;
        max-width: 750px;
        padding: -15px 40px;
        margin: 20px 300px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(8, 8, 8, 0.55);
        height: 700px;
    }

    h2 {
        text-align: center;
        color: #1ec9e7;
        margin-top: 10px;
        margin-bottom: 5px;
        font-weight: bolder;
        text-decoration: underline;
    }

    .form-label {
        color: #004080;
        font-weight: bold;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ccc;
        padding: -15px;
        margin-top: 0px;
    }

    .form-control:focus {
        border-color: #1ec9e7;
        box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.2);
    }

    .btn-primary {
        background-color: #1ec9e7;
        border: none;
        padding: 5px 5px;
        border-radius: 8px;
        width: 200px;
        font-size: 16px;
        font-weight: bolder;
    }

    .btn-primary:hover {
        background-color: #238596;
    }

    .swal2-popup {
        font-size: 16px !important;
    }
</style>

<div class="container"><br>
    <h2>Book a Room for Meeting</h2><br>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonText: 'Done',
                confirmButtonColor: '#42CCC5',
                background: '#fff',
                iconColor: '#42CCC5',
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        </script>
    @endif


    <form id="booking-form" action="{{ route('admin.add-store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="requester" class="form-label">Person Requesting / Title</label>
            <input type="text" name="requester" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="meeting_room" class="form-label">Select Meeting Room</label>
            <select name="meeting_room_id" class="form-control" required>
                <option value="">Choose a Room</option>
                @if(isset($rooms) && $rooms->count() > 0)
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }} - Room {{ $room->room_number }} (Floor {{ $room->floor }})</option>
                    @endforeach
                @else
                    <option value="">No meeting rooms available</option>
                @endif
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date" class="form-label">Select Date</label>
                <input type="text" id="date" name="date" class="form-control datepicker" required>
            </div>

            <div class="col-md-4">
                <label for="time" class="form-label">Select Time</label>
                <input type="text" id="time" name="time" class="form-control timepicker" required>
            </div>

            <div class="col-md-4">
                <label for="duration" class="form-label">Duration (in hours)</label>
                <input type="number" name="duration" class="form-control" min="1" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="extension" class="form-label">Extension Number</label>
            <input type="text" name="extension" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="capacity" class="form-label">Number of People</label>
            <input type="number" name="capacity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Reason for Booking</label>
            <textarea name="reason" class="form-control" required></textarea>
        </div><br>

        <button type="submit" class="btn btn-primary">Book Now</button>
    </form>
</div>

<!-- Flatpickr Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Date Picker
    flatpickr("#date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr) {
            checkAvailability(dateStr, document.getElementById("time").value);
        }
    });

    // Time Picker (with AM/PM)
    flatpickr("#time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false,
        onChange: function(selectedDates, timeStr) {
            checkAvailability(document.getElementById("date").value, timeStr);
        }
    });

    // Availability Check
    function checkAvailability(date, time) {
        let meetingRoomId = document.querySelector("[name='meeting_room_id']").value;

        if (date && time && meetingRoomId) {
            fetch("{{ route('bookings.checkAvailability') }}?date=" + date + "&time=" + time + "&meeting_room_id=" + meetingRoomId)
                .then(response => response.json())
                .then(data => {
                    if (data.conflict) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Time Slot Unavailable',
                            text: "This room is already booked at the selected time! Please choose another slot.",
                        });
                        document.getElementById("time").value = "";
                    }
                });
        }
    }
});
</script>
@endsection
