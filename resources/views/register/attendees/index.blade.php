@extends('register.layouts.app')
@section('content')
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<div class="container" style="display: flex; gap: 20px;">
    <div style="flex: 2;">
        <h1>Approved Bookings</h1>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 0px;">
            <form action="{{ route('register.attendees.index') }}" method="GET" style="display: flex; gap: 10px;">
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
                        <th>E-Ticket</th>
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
                            </td>
                            <td>
                                @if($booking->e_ticket)
                                    <span id="ticket-{{ $booking->id }}" style="color: green; font-weight: bold;">{{ $booking->e_ticket }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success">Approved</span>
                            </td>
                            <td>
                                <a href="{{ route('register.attendees.register', $booking->id) }}" class="btn">Load</a>
                                <form action="{{ route('register.attendees.uploadFlyerForBooking', $booking->id) }}" method="POST" enctype="multipart/form-data" style="display:inline-block; margin-left: 10px;">
                                    @csrf
                                    <input type="file" name="flyer" accept="image/*" required style="display: inline-block; width: 120px; padding: 3px;">
                                    <button type="submit" class="btn" style="padding: 5px 10px; margin-left: 5px;">Upload Flyer</button>
                                </form>
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
@include('register.layouts.footer')
