@extends('layouts.admin-sidebar')
@section('content')
<div class="container">
    <h1>Meeting Attendees for {{ $booking->requester }}</h1>
    <p><strong>Date:</strong> {{ $booking->date }} | <strong>Time:</strong> {{ $booking->time }} | <strong>Room:</strong> {{ $booking->meetingRoom->name ?? 'N/A' }}</p>

    <div style="width: 100%; display: flex; justify-content: flex-end; margin-bottom: 0px;">
        <form action="{{ route('admin.attendees.view', $booking->id) }}" method="GET" class="search-bar-responsive" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone" style="flex:1; padding: 8px 12px; border: 1px solid #42CCC5; border-radius: 5px; min-width:0;">
            <button type="submit"
                    style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px;">
                🔍 Search
            </button>
            @if(request('search'))
            <a href="{{ route('admin.attendees.view', $booking->id) }}" class="clear-search" style="display: flex; align-items: center; color: #42CCC5; text-decoration: underline; font-weight: 600;">Clear</a>
            @endif
        </form>
    </div>

    @if($attendees->count())
    <div class="table-responsive"><div class="table-wrapper" style="overflow-x:auto;">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Department / Company</th>
                    <th>Phone</th>
                    <th>Registration Time</th>
                    <th>Status</th>
                    <th>Verification Code</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->name ?? 'N/A'}}</td>
                        <td>{{ $attendee->gender ?? 'N/A'}}</td>
                        <td>{{ $attendee->email ?? 'N/A'}}</td>
                        <td>{{ $attendee->department ?? 'N/A'}}</td>
                        <td>{{ $attendee->phone ?? 'N/A'}}</td>
                        <td>{{ $attendee->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($attendee->status === 'present')
                                <span style="color:green;font-weight:bold;">Present</span>
                            @else
                                <span style="color:gray;">Not Present</span>
                            @endif
                        </td>
                        <td>
                            @if($attendee->status === 'present')
                                <span style="color:green;font-weight:bold;">{{ $attendee->meeting_code }}</span>
                            @else
                                <span style="color:rgb(255, 0, 0);">{{ $attendee->meeting_code }}</span>
                            @endif
                        </td>
                        {{-- <td>{{ $attendee->meeting_code ?? 'N/A'}}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div></div><br>
    @else
        <p><strong>No attendees have been registered for this meeting yet.</strong></p>
    @endif

    {{-- <a href="{{ route('admin.registration') }}" class="bn">← Back</a> --}}
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

    .bn:hover {
        color:red;
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

    h1 {
        text-align: center;
        font-size: 2.1rem;
        font-weight: 800;
        color: #42CCC5;
        margin-top: 30px;
        margin-bottom: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .container > p {
        text-align: center;
        font-size: 1.13rem;
        color: #555;
        margin-bottom: 28px;
        margin-top: 0;
        font-weight: 500;
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
        .search-bar-responsive button[type="submit"],
        .search-bar-responsive .clear-search {
            width: 100% !important;
            margin-bottom: 8px !important;
            text-align: center;
        }
        .search-bar-responsive .clear-search {
            margin-bottom: 0 !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

