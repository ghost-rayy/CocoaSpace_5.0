@extends('layouts.admin-sidebar')

@section('content')
    <div class="container">
        @if ($pendingBookings->isEmpty())
            <p>No requests available yet.</p>
        @else
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
        @endif

            <h1>Pending Requests</h1>
            
            {{-- <div class="table-wrapper">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>Requester</th>
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
            </div> --}}

            <div id="pending-bookings-table">
                @include('admin.partials.pending-table', ['pendingBookings' => $pendingBookings])
            </div>

        @endif
    </div>
@endsection

<style>

h1 {
    text-align: center;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgb(0, 0, 0);
    padding: 30px 0;
}

p{
    text-align: center;
    /* color: red; */
}

 .table-wrapper {
        max-height: auto;
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .fl-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fl-table thead th {
        position: sticky;
        top: 0;
        background-color: #42CCC5;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 12px;
        z-index: 2;
    }

    .fl-table th, .fl-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f4faff;
    }

    .fl-table tbody tr:hover {
        background-color: #e0f0ff;
    }

    @media (max-width: 768px) {
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>
