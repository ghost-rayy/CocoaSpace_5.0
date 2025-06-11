@if ($pendingBookings->isEmpty())
    <p style="color: red;">No requests available yet.</p>
@else
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Requester / Title</th>
                    <th>Duration</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Extension</th>
                    <th>Reason</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingBookings as $booking)
                    <tr>
                        <td>{{ $booking->requester }}</td>
                        <td>{{ $booking->duration }}</td>
                        <td>{{ $booking->date }}</td>
                        <td>{{ $booking->time }}</td>
                        <td>{{ $booking->extension }}</td>
                        <td>{{ $booking->reason }}</td>
                        <td>{{ $booking->capacity }}</td>
                        <td>
                            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $booking->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Declined" {{ $booking->status == 'Declined' ? 'selected' : '' }}>Declined</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="badge bg-{{ $booking->status == 'Approved' ? 'success' : ($booking->status == 'Declined' ? 'danger' : 'warning') }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
