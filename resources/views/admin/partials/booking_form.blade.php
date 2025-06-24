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

<form id="booking-form" action="{{ route('admin.add-store') }}" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
    @csrf
    <input type="text" name="requester" class="form-control" placeholder="Person Requesting / Title" required>
    <select name="meeting_room_id" class="form-control" required>
        <option value="">Select Meeting Room</option>
        @foreach($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }} - Room {{ $room->room_number }} ({{ $room->floor }})</option>
        @endforeach
    </select>
    <input type="date" name="date" class="form-control" placeholder="Select Date" required style="margin-top: 20px;">
    <input type="time" name="time" class="form-control" placeholder="Select Time" required style="margin-top: 20px;">
    <input type="number" name="duration" class="form-control" placeholder="Duration (In hours)" min="1" style="margin-top: 20px;">
    <input type="text" name="extension" class="form-control" placeholder="Extension Number">
    <input type="number" name="capacity" class="form-control" placeholder="Number of People">
    <textarea name="reason" class="form-control" placeholder="Reason for Booking" required style="min-height: 70px;"></textarea>
    <button type="submit" class="btn-primary" style="width: 100%; margin-top: 8px;">Submit</button>
</form>

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

<style>
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 0;
    background: #fff;
    color: #333;
    box-sizing: border-box;
    transition: border-color 0.2s;
}
.form-control:focus {
    border-color: #42CCC5;
    outline: none;
    box-shadow: 0 0 5px rgba(66, 204, 197, 0.2);
}
.btn-primary {
    background-color: #42CCC5;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    padding: 10px 0;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-primary:hover {
    background-color: #36b3ad;
}
</style>

