<!DOCTYPE html>
<html lang="en">
<head>
        @extends('layouts.user-sidebar')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Bookings</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #e0f7f9;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: left;
            color: #000;
            font-weight: bolder;
            text-transform: none;
            font-size: 24px;
            padding-bottom: 10px;
            width: fit-content;
            margin-left: 150px;
        }

        .table-wrapper {
            width: 95%;
            max-height: 540px;
            overflow: auto;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            border: none;
            margin-left: 2.5%;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .table-wrapper:hover {
            scrollbar-width: thin;
        }

        .table-wrapper:hover::-webkit-scrollbar {
            display: block;
            width: 8px;
        }

        .table-wrapper:hover::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar {
            width: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .table-wrapper:hover::-webkit-scrollbar {
            opacity: 100;
        }



        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            font-family: 'Segoe UI', sans-serif;
        }

        thead tr {
            position: sticky;
            top: 0;
            background: #7ed6df;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            border-radius: 15px;
            z-index: 10;
        }

        thead th {
            padding: 15px 20px;
            text-align: center;
        }

        tbody tr {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0, 255, 238, 0.949);
            text-align: center;
        }

        tbody tr td {
            padding: 15px 20px;
            vertical-align: top;
            color: #000;
        }

        tbody tr td .room-subtext {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
        }

        .status-label {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            color: white;
            text-align: center;
            width: 80px;
        }

        .status-label.pending {
            background-color: #d3d3d3;
            color: #555;
        }

        .status-label.approved {
            background-color: #4caf50;
        }

        .status-label.cancelled {
            background-color: #f44336;
        }

        .action-button {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            color: white;
            background-color: #00bcd4;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-button.cancel {
            background-color: #00bcd4;
        }

        .action-button.rebook {
            background-color: #2196f3;
        }

        .action-button:hover {
            opacity: 0.8;
        }

        @media (max-width: 767px) {
            .table-wrapper {
                max-height: 300px;
            }
        }

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
</head>
<body>

    @section('content')
        <div class="header-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; margin-right: 150px;">
            <h2 style="font-size: 30px; font-weight:bolder;">My Bookings</h2>
            <div class="header-controls" style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('booking.create') }}" class="btn-book-room" style="background-color: #00bcd4; color: white; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease;">Book a Room</a>
            </div>
        </div>

        <div class="table-responsive">
            <div class="table-wrapper">
                <table id="bookingsTable">
                    <thead>
                        <tr>
                            <th>Room Name</th>
                            <th>Floor</th>
                            <th>Room Number</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Meeting ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                {{ $booking->meetingRoom->name ?? 'N/A' }}
                                </td>
                                <td>{{ $booking->meetingRoom->floor ?? '-' }}</td>
                                <td>{{ $booking->meetingRoom->room_number ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->date)->format('jS F, Y') }}</td>
                                <td>{{ $booking->time }}</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        if (strtolower($booking->status) === 'pending') {
                                            $statusClass = 'pending';
                                        } elseif (strtolower($booking->status) === 'approved') {
                                            $statusClass = 'approved';
                                        } elseif (strtolower($booking->status) === 'cancelled') {
                                            $statusClass = 'cancelled';
                                        }
                                    @endphp
                                    <span class="status-label {{ $statusClass }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($booking->e_ticket)
                                        <span>{{ $booking->e_ticket }}</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
});
</script>
</body>
</html>
