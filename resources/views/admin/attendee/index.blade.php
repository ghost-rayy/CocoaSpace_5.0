@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: true,
        confirmButtonText: 'Done',
        confirmButtonColor: '#42CCC5',
        background: '#fff',
        iconColor: '#42CCC5'
    });
</script>
@endif

<div class="container">
    <h1>Approved Bookings</h1>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 0px;">
        <form action="{{ route('admin.registration') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or e-ticket" style="padding: 8px 12px; border: 1px solid #42CCC5; border-radius: 5px;">
            <button type="submit"
                    style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px;">
                🔍 Search
            </button>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Requester</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Meeting ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->requester }}</td>
                        <td>{{ $booking->date }}</td>
                        <td>{{ $booking->time }}</td>
                        <td>
                            {{ $booking->meetingRoom->name ?? 'N/A' }}<br>
                            {{-- Room No: {{ $booking->meetingRoom->room_number ?? '-' }}<br>
                            Floor: {{ $booking->meetingRoom->floor ?? '-' }} --}}
                        </td>
                        <td>
                            @if($booking->e_ticket)
                                <span id="ticket-{{ $booking->id }}" style="color: green; font-weight: bold;">{{ $booking->e_ticket }}</span>
                                {{-- <button onclick="copyToClipboard({{ $booking->id }})" class="btn btn-sm btn-outline-secondary">Copy</button> --}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">Approved</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.attendees.register', $booking->id) }}" class="btn">Load</a>
                            <a href="{{ route('admin.attendees.view', $booking->id) }}" class="btn">View Attendance</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: red; font-weight: bold;">
                            No approved bookings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<script>
    function copyToClipboard(id) {
        const text = document.getElementById("ticket-" + id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert("E-Ticket copied!");
        });
    }
</script>

<style>
    h1 {
        text-align: center;
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        padding: 5px 0;
    }

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
        text-align: center;
        padding: 12px;
        z-index: 2;
    }

    .fl-table th, .fl-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
        text-transform: uppercase;
    }

    .fl-table tbody tr:nth-child(even) {
        background-color: #f4faff;
    }

    .fl-table tbody tr:hover {
        background-color: #e0f0ff;
    }

    .btn:hover{
        color: #fcfcfc;
        background-color: #1f6d69;
    }

    .btn{
        text-decoration: none; 
        background-color:#42CCC5; 
        padding:5px; 
        color:white;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>
