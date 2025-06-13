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

<div class="container" style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 2; min-width: 300px;">
        <h1>Approved Bookings</h1>

            <div class="search-container">
            <form class="search-form" action="{{ route('register.attendees.index') }}" method="GET" >
                <input class="search-input" type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or e-ticket" >
                <button type="submit" class="search-button">🔍 Search</button>

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
                            </td>
                            <td>
                                @if($booking->e_ticket)
                                    <span id="ticket-{{ $booking->id }}" style="color: green; font-weight: bold;">{{ $booking->e_ticket }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success" style="padding: 10px;">Approved</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a style="background-color: #1f6d69; color:white;" href="{{ route('register.attendees.register', $booking->id) }}" class="btn">Load</a>
                                    <form action="{{ route('register.attendees.uploadFlyerForBooking', $booking->id) }}" method="POST" enctype="multipart/form-data" class="upload-flyer-form">
                                        @csrf
                                        <input type="file" name="flyer" accept="image/*" required id="flyer-{{ $booking->id }}" class="file-input">
                                        <label for="flyer-{{ $booking->id }}" class="file-label">Upload Flyer</label>
                                    </form>
                                </div>
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

<style>
    @media (max-width: 768px) {
        .table-wrapper {
            max-height: none;
            overflow: visible;
        }
        .fl-table {
            font-size: 12px;
        }
        .fl-table th, .fl-table td {
            padding: 6px;
        }
        form[method="GET"] {
            flex-direction: column;
            align-items: stretch;
        }
        form[method="GET"] input[type="text"], form[method="GET"] button {
            width: 100% !important;
            margin-bottom: 10px;
        }
    }
</style>

<script>
    if (window.matchMedia('(display-mode: standalone)').matches) {
        const searchBar = document.getElementById('search-bar-container');
        if (searchBar) {
            searchBar.style.display = 'none';
        }
    }
</script>
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
    .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            width: 100%;
        }

        .search-input {
            padding: 12px 16px;
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            flex: 1;
            min-width: 200px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .search-input:focus {
            border-color: #42CCC5;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(66, 204, 197, 0.1);
            outline: none;
        }

        .search-button {
            padding: 12px 20px;
            background-color: #42CCC5;
            border: none;
            color: white;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background-color: #1f6d69;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 204, 197, 0.3);
        }
        
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
        background-color: #42CCC5;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.file-input').forEach(function(input) {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>

<style>
    .action-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .upload-flyer-form {
        display: flex;
        gap: 5px;
        align-items: center;
        margin: 0;
        background-color: #1f6d69;
        border-radius: 8px;
    }

    .file-label {
        color: white;
        padding:8px;
        cursor: hand;
        border-radius: 8px;
    }

    .file-label:hover{
        background-color: #42CCC5
    }

    .file-input {
        display: none;
    }

    @media (max-width: 480px) {
        .action-buttons {
            flex-direction: column;
            gap: 8px;
        }

        .upload-flyer-form {
            width: 100%;
            justify-content: center;
        }

        .file-label, .upload-btn, .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

@include('register.layouts.footer')
