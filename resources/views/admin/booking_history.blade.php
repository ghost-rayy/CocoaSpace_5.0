@extends('layouts.admin-sidebar')

@section('content')
    <div class="container">
        <h1 style="font-weight:bolder; text-transform:uppercase; text-align:center;">Booking History</h1>
        <div style="width:100%; display: flex; justify-content: flex-end; margin-bottom: 0px;">
            <form action="{{ route('admin.bookings.history') }}" method="GET" class="search-bar-responsive" style="display: flex; gap: 10px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or e-ticket" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 5px;">
                <button type="submit"
                        style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px;">
                    🔍 Search
                </button>
            </form>
        </div>

        <div class="table-responsive"><div class="table-wrapper" style="overflow-x:auto;">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>Requester / Title</th>
                        <th>Duration</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Extension</th>
                        <th>Reason</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>E-Ticket</th>
                        <th>Meeting Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookingHistories as $booking)
                        <tr>
                            <td>{{ $booking->requester }}</td>
                            <td>{{ $booking->duration }}</td>
                            <td>{{ $booking->date }}</td>
                            <td>{{ $booking->time }}</td>
                            <td>
                                {{ $booking->meetingRoom->name ?? 'N/A' }}<br>
                                Room No: {{ $booking->meetingRoom->room_number ?? '-' }}<br>
                                Floor: {{ $booking->meetingRoom->floor ?? '-' }}
                            </td>
                            <td>{{ $booking->extension }}</td>
                            <td>{{ $booking->reason }}</td>
                            <td>{{ $booking->capacity }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status == 'Approved' ? 'success' : ($booking->status == 'Declined' ? 'danger' : 'warning') }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td>
                                @if($booking->e_ticket)
                                    <span style="color: green; font-weight: bold;">{{ $booking->e_ticket }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">Ended</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align: center; color: red; font-weight: bold;">
                                No booking history found for your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
    </div>
@endsection

<style>
    .table-wrapper {
        max-height: 700px;
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
        text-align: left;
        padding: 12px;
        z-index: 2;
    }

    .fl-table th, .fl-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f4faff;
    }

    .fl-table tbody tr:hover {
        background-color: #e0f0ff;
    }

    /* Optional: Improve responsiveness */
    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table-responsive table {
            min-width: 600px;
        }
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }

    @media (max-width: 600px) {
        .search-bar-responsive {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100% !important;
        }
        .search-bar-responsive input[type="text"] {
            width: 100% !important;
            margin-bottom: 8px !important;
        }
        .search-bar-responsive button[type="submit"] {
            width: 100% !important;
        }
    }
</style>
