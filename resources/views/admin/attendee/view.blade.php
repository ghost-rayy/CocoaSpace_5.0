@extends('layouts.admin-sidebar')
@section('content')
<div class="container">
    <h1>Meeting Attendees for {{ $booking->requester }}</h1>
    <p><strong>Date:</strong> {{ $booking->date }} | <strong>Time:</strong> {{ $booking->time }} | <strong>Room:</strong> {{ $booking->meetingRoom->name ?? 'N/A' }}</p>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 0px;">
        <form action="{{ route('admin.attendees.view', $booking->id) }}" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone" style="padding: 8px 12px; border: 1px solid #42CCC5; border-radius: 5px;">
            <button type="submit"
                    style="padding: 8px 15px; background-color: #42CCC5; border: none; color: white; border-radius: 5px;">
                🔍 Search
            </button>
            @if(request('search'))
            <a href="{{ route('admin.attendees.view', $booking->id) }}" class="clear-search">
                Clear
            </a>
            @endif
        </form>
    </div>

    @if($attendees->count())
    <div class="table-wrapper">

        <table class="fl-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Department / Company</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->name }}</td>
                        <td>{{ $attendee->gender }}</td>
                        <td>{{ $attendee->email }}</td>
                        <td>{{ $attendee->department }}</td>
                        <td>{{ $attendee->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div><br>
    @else
        <p><strong>No attendees have been registered for this meeting yet.</strong></p>
    @endif

    <a href="{{ route('admin.registration') }}" class="bn">← Back</a>
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

    .bn:hover {
        color:red;
    }

    /* Optional: Improve responsiveness */
    @media (max-width: 768px) {
        .fl-table th, .fl-table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>

