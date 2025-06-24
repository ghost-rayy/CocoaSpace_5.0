@extends('layouts.admin-sidebar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@section('content')
    <div class="container">
        <h1 style="font-weight:bolder; text-transform:uppercase; text-align:center;">Manage Bookings</h1>
        <div style="width: 100%; display: flex; justify-content: flex-end; margin-bottom: 0;">
            <form action="{{ route('admin.bookings') }}" method="GET" class="search-bar-responsive" style="display: flex; gap: 10px; width: 100%; max-width: 500px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or e-ticket" style="flex:1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 5px; min-width:0;">
                <button type="submit"
                        style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px;">
                    🔍 Search
                </button>
            </form>
        </div>


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
                    <th>Meeting ID</th>
                    {{-- <th>Action</th> --}}
                    <th>Meeting Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->requester }}</td>
                        <td>{{ $booking->duration }}</td>
                        <td>{{ $booking->date }}</td>
                        <td>{{ $booking->time }}</td>
                        <td>
                            {{ $booking->meetingRoom->name ?? 'N/A' }}<br>
                            {{-- Room No: {{ $booking->meetingRoom->room_number ?? '-' }}<br>
                            Floor: {{ $booking->meetingRoom->floor ?? '-' }} --}}
                        </td>
                        <td>{{ $booking->extension }}</td>
                        <td>{{ $booking->reason }}</td>
                        <td>{{ $booking->capacity }}</td>
                        <td>
                            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-select" onchange="this.form.submit()" style="background-color: none; color: rgb(0, 0, 0); border:none; cursor:hand; font-size:15px; width:100%;">
                                    <option style="font-size:10px;" value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option style="font-size:10px;" value="Approved" {{ $booking->status == 'Approved' ? 'selected' : '' }}>Approve</option>
                                    <option style="font-size:10px;" value="Declined" {{ $booking->status == 'Declined' ? 'selected' : '' }}>Decline</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if($booking->e_ticket)
                                <span id="ticket-{{ $booking->id }}" style="color: green; font-weight: bold;">{{ $booking->e_ticket }}</span>
                            @else
                                N/A
                            @endif
                        </td>
                        {{-- <td>
                            <span class="badge bg-{{ $booking->status == 'Approved' ? 'success' : ($booking->status == 'Declined' ? 'danger' : 'warning') }}">
                                {{ $booking->status }}
                            </span>
                        </td> --}}
                        <td>
                            @if($booking->status == 'Approved' && !$booking->meeting_ended)
                                <form action="{{ route('admin.bookings.endMeeting', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" style="background-color: red; color:white; padding:4px; border: none; cursor: hand;">End Meeting</button>
                                </form>
                            @elseif($booking->meeting_ended)
                                <span class="badge bg-secondary">Ended</span>
                            @else
                                <span class="badge bg-{{ $booking->status == 'Declined' ? 'danger' : 'warning' }}">{{ $booking->status }}</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" style="text-align: center; color: red; font-weight: bold;">
                            No meetings found for your search.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div></div>
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
        .table-wrapper {
            max-height: auto;
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
            padding: 10px;
            z-index: 2;
        }

        .fl-table th, .fl-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #0ad2ed;
            font-size: 14px;
            text-transform: uppercase;
        }

        .fl-table tbody tr:nth-child(even) {
            /* background-color: #f4faff; */
        }

        .fl-table tbody tr:hover {
            background-color: #e0f0ff;
        }

        .table-responsive {
            width: 100%;
        }
        .table-responsive table {
            width: 100%;
        }

        @media (max-width: 768px) {
            .table-responsive {
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
