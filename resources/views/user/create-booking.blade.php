<!DOCTYPE html>
<html lang="en">
<head>
        @extends('layouts.user-sidebar')

    <meta charset="UTF-8">
    <title>Book a Meeting Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4faff;
            margin: 0;
            padding: 0;
        } */

        .container {
            display: flex;
            max-width: 1300px;
            margin: 40px auto;
            border-radius: 12px;
            overflow: hidden;
            height: 540px;
            /* margin-top:20px; */
        }

        .image-section {
            flex: 1;
            background: #f5faff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin-top: -100px;
        }

        .image-section img {
            max-width: 100%;
            height: 75%;
            border-radius: 12px;
        }

        .form-section {
            flex: 1;
            padding: 40px 30px;
            background: #ffffff;
            height: 100%;
            border-radius: 20px;
        }

        .form-section h2 {
            color: #42CCC5;
            font-weight: bolder;
            text-decoration: underline;
            text-align: center;
            margin-top: -35px;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 10px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 243, 137, 0.85);
            color: #000;
            text-transform: uppercase;
        }

        .form-control:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .btns {
            background-color: #42CCC5;
            width: 30%;
            padding: 5px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            color: white;       
        }

        .btns:hover {
            background-color: #236e73;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .col {
            flex: 1;
            min-width: 100px;
        }

        label {
            font-weight: bolder;
            display: block;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .btnss {
            width: 150%;
            padding: 5px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px; 
            background-color: #42CCC5;
            color: white;       
        }

        .btnss:hover {
            background-color: #236e73;
        }
    </style>
</head>
<body>

    @section('content')
        <div class="container">
            <div class="image-section" style="display: flex; flex-direction: column; align-items: center;">
                <img src="{{ asset('images/c1.png') }}" alt="Meeting Room Illustration">
                <a href="{{ route('user.booking') }}">
                    <button class="btnss">My Bookings</button>
                </a>
            </div>
            
            <div class="form-section">
                <h2>Book a Meeting Room</h2>

                @if(session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '{{ session('error') }}',
                        });
                    </script>
                @endif

                @if(session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '{{ session('success') }}',
                        });
                    </script>
                @endif

                <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                    @csrf

                    <label for="requester">Person Requesting / Meeting Title</label>
                    <input type="text" name="requester" class="form-control" required>

                    <label for="meeting_room_id">Select Meeting Room</label>
                    <select name="meeting_room_id" class="form-control" required>
                        <option value="">Choose a Room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }} - Room {{ $room->room_number }} ( {{ $room->floor }})</option>
                        @endforeach
                    </select>

                    <div class="row">
                        <div class="col">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" class="form-control datepicker" required>
                        </div>
                        <div class="col">
                            <label for="time">Time</label>
                            <input type="time" id="time" name="time" class="form-control timepicker" required>
                        </div>
                        <div class="col">
                            <label for="duration">Duration (hours)</label>
                            <input type="number" name="duration" class="form-control" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">

                            <label for="extension">Extension Number</label>
                            <input type="text" name="extension" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="capacity">Number of People</label>
                            <input type="number" name="capacity" class="form-control" required>
                        </div>
                    </div>

                    <label for="reason">Reason for Booking</label>
                    <textarea name="reason" class="form-control" required></textarea>

                    <button type="submit" class="btns">Book Now</button>
                </form>
            </div>
        </div>
    @endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date", {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function(selectedDates, dateStr) {
                checkAvailability(dateStr, document.getElementById("time").value);
            }
        });

        flatpickr("#time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false,
            onChange: function(selectedDates, timeStr) {
                checkAvailability(document.getElementById("date").value, timeStr);
            }
        });

        function checkAvailability(date, time) {
            let roomId = document.querySelector("[name='meeting_room_id']").value;
            if (date && time && roomId) {
                fetch(`{{ route('bookings.checkAvailability') }}?date=${date}&time=${time}&meeting_room_id=${roomId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.conflict) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Time Slot Unavailable',
                            text: 'This room is already booked at the selected time!',
                        });
                        document.getElementById("time").value = "";
                    }   
                });
            }
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
});
</script>

</body>
</html>
