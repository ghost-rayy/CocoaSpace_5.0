@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    </script>
@endif
@if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ $errors->first() }}',
        });
    </script>
@endif

<form action="{{ route('admin.upload-document') }}" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:14px; min-width:260px;">
    @csrf
    <label for="booking_id">Select Booking</label>
    <select name="booking_id" id="booking_id" class="form-control" required>
        <option value="">-- Select Booking --</option>
        @foreach($bookings as $booking)
            <option value="{{ $booking->id }}">
                {{ $booking->requester }} | {{ $booking->date }} | {{ $booking->time }}
            </option>
        @endforeach
    </select>
    <label for="document">Attach Document</label>
    <input type="file" name="document" id="document" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
    <button type="submit" class="btn-primary" style="margin-top:10px;">Upload</button>
</form> 