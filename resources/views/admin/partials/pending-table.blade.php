@if ($pendingBookings->isEmpty())
    <p style="color: red;">No requests available yet.</p>
@else
    <div class="table-responsive"><div class="table-wrapper" style="overflow-x:auto;">
        <table class="fl-table">
            <thead>
                <tr>
                    <th style="text-align: left">Requester / Title</th>
                    <th style="text-align: left">Duration</th>
                    <th style="text-align: left">Date</th>
                    <th style="text-align: left">Time</th>
                    <th style="text-align: left">Extension</th>
                    <th style="text-align: left">Reason</th>
                    <th style="text-align: left">Capacity</th>
                    <th style="text-align: left">Status</th>
                    <th style="text-align: left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingBookings as $booking)
                    <tr>
                        <td style="text-align: left">{{ $booking->requester }}</td>
                        <td style="text-align: left">{{ $booking->duration }}</td>
                        <td style="text-align: left">{{ $booking->date }}</td>
                        <td style="text-align: left">{{ $booking->time }}</td>
                        <td style="text-align: left">{{ $booking->extension }}</td>
                        <td style="text-align: left">{{ $booking->reason }}</td>
                        <td style="text-align: left">{{ $booking->capacity }}</td>
                        <td style="text-align: left">
                            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $booking->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Declined" {{ $booking->status == 'Declined' ? 'selected' : '' }}>Declined</option>
                                </select>
                            </form>
                        </td>
                        <td style="text-align: left">
                            <span class="badge bg-{{ $booking->status == 'Approved' ? 'success' : ($booking->status == 'Declined' ? 'danger' : 'warning') }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div></div>
@endif

<style>
    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table-responsive table {
            min-width: 600px;
        }
    }
</style>
