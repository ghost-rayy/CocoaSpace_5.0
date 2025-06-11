{{-- @if(session('success'))
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
        <label for="requester" class="form-label">Person Requesting / Title</label>
        <input type="text" name="requester" class="form-control" required>

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
</form> --}}

<div class="container">            
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

        <form id="booking-form" action="{{ route('admin.add-store') }}" method="POST">
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

            <div class="column">
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
</div>

<style>
    .container {
        max-width: 1300px;
        margin: 40px auto;
        border-radius: 12px;
        height: 520px;
        /* margin-top:20px; */
    }

    .form-section {
            /* flex: 1; */
            /* padding: 40px 30px; */
            /* background: #ffffff; */
            /* height: 100%; */
            border-radius: 20px;
    }
    
    .form-section h2 {
        color: #000000;
        font-size: 20px;
        text-align: center;
        margin-top: -60px;
        margin-bottom: 10px;
    }

    .form-control {
        width: 90%;
        padding: 10px 12px;
        margin-bottom: 10px;
        border: none;
        border-radius: 8px;
        color: #000;
        text-transform: uppercase;
    }

    .form-control:focus {
        outline: none;
        background-color: rgba(255, 255, 255, 0.95);
    }

    .btns {
        background-color: turquoise;
        width: 30%;
        padding: 10px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: -15px;
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
        background-color: turquoise;
        color: white;       
    }

    .btnss:hover {
        background-color: #236e73;
    }
</style>

