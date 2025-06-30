@extends('layouts.admin-sidebar')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<div class="modern-container">
    <div class="modern-info-bar">
        <div>
            <h1>Meeting Attendees</h1>
            <div class="modern-meeting-details">
                <span><strong>Requester / Title:</strong> {{ $booking->requester }}</span>
                <span><strong>Date:</strong> {{ $booking->date }}</span>
                <span><strong>Time:</strong> {{ $booking->time }}</span>
                <span><strong>Room:</strong> {{ $booking->meetingRoom->name ?? 'N/A' }}</span>
            </div>
        </div>
        <form action="{{ route('admin.attendees.view', $booking->id) }}" method="GET" class="modern-search-form">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone" class="modern-search-input">
            <button type="submit" class="modern-search-btn">🔍 Search</button>
            @if(request('search'))
            <a href="{{ route('admin.attendees.view', $booking->id) }}" class="modern-clear-btn">Clear</a>
            @endif
        </form>
    </div>

    @if($attendees->count())
    <div class="modern-table-wrapper">
        <table class="modern-table">
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
                    <tr class="modern-card-row">
                        <td>{{ $attendee->name ?? 'N/A'}}</td>
                        <td>{{ $attendee->gender ?? 'N/A'}}</td>
                        <td>{{ $attendee->email ?? 'N/A'}}</td>
                        <td>{{ $attendee->department ?? 'N/A'}}</td>
                        <td>{{ $attendee->phone ?? 'N/A'}}</td>
                        <td>{{ $attendee->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($attendee->status === 'present')
                                <span class="modern-badge badge-present">Present</span>
                            @else
                                <span class="modern-badge badge-notpresent">Not Present</span>
                            @endif
                        </td>
                        <td>
                            @if($attendee->status === 'present')
                                <span class="modern-code code-present">{{ $attendee->meeting_code }}</span>
                            @else
                                <span class="modern-code code-notpresent">{{ $attendee->meeting_code }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div><br>
    @else
        <p class="modern-empty"><strong>No attendees have been registered for this meeting yet.</strong></p>
    @endif
</div>
@endsection

<style>
body, .modern-container {
    font-family: 'Inter', Arial, sans-serif;
    background: #f7fafd;
}
.modern-container {
    max-width: 1300px;
    margin: 40px auto 0 auto;
    padding: 32px 24px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(34, 197, 194, 0.08), 0 1.5px 4px rgba(0,0,0,0.04);
}
.modern-info-bar {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 32px;
}
.modern-info-bar h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #0f766e;
    margin: 0 0 8px 0;
    letter-spacing: 1px;
    text-align: left;
}
.modern-meeting-details {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    font-size: 1.08rem;
    color: #444;
    margin-bottom: 0;
    font-weight: 500;
}
.modern-search-form {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 12px 16px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(34, 197, 194, 0.04);
    margin-top: 8px;
}
.modern-search-input {
    padding: 10px 14px;
    border: 1.5px solid #42CCC5;
    border-radius: 7px;
    font-size: 1rem;
    outline: none;
    transition: border 0.2s;
    background: #fff;
}
.modern-search-input:focus {
    border: 1.5px solid #0f766e;
}
.modern-search-btn {
    padding: 10px 18px;
    background: linear-gradient(90deg, #42CCC5 60%, #0f766e 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    box-shadow: 0 2px 8px rgba(34, 197, 194, 0.08);
}
.modern-search-btn:hover {
    background: linear-gradient(90deg, #0f766e 60%, #42CCC5 100%);
}
.modern-clear-btn {
    padding: 10px 18px;
    background: #fff;
    color: #0f766e;
    border-radius: 7px;
    font-weight: 600;
    text-decoration: underline;
    border: 1.5px solid #42CCC5;
    margin-left: 8px;
    transition: background 0.2s, color 0.2s;
}
.modern-clear-btn:hover {
    background: #42CCC5;
    color: #fff;
}
.modern-table-wrapper {
    margin-top: 18px;
    overflow-x: auto;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(34, 197, 194, 0.06);
    background: #f9f9fb;
}
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}
.modern-table thead th {
    background: #42CCC5;
    color: #fff;
    font-weight: 600;
    padding: 14px 10px;
    border-radius: 8px 8px 0 0;
    font-size: 1rem;
    text-align: left;
    letter-spacing: 0.5px;
}
.modern-table tbody tr.modern-card-row {
    background: #fff;
    box-shadow: 0 2px 8px rgba(34, 197, 194, 0.07);
    border-radius: 8px;
    transition: box-shadow 0.2s;
}
.modern-table tbody tr.modern-card-row:hover {
    box-shadow: 0 4px 16px rgba(34, 197, 194, 0.13);
}
.modern-table td {
    padding: 14px 10px;
    font-size: 1rem;
    color: #222;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}
.modern-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.95rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: capitalize;
}
.badge-present {
    background: linear-gradient(90deg, #42CCC5 60%, #0f766e 100%);
    color: #fff;
}
.badge-notpresent {
    background: #e5e7eb;
    color: #222;
}
.modern-code {
    font-weight: 700;
    font-size: 1.05rem;
    letter-spacing: 1px;
    padding: 6px 12px;
    border-radius: 8px;
    display: inline-block;
}
.code-present {
    background: #d1fae5;
    color: #059669;
}
.code-notpresent {
    background: #fee2e2;
    color: #dc2626;
}
.modern-empty {
    text-align: center;
    color: #ef4444;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 32px 0;
}
@media (max-width: 900px) {
    .modern-info-bar {
        flex-direction: column;
        gap: 10px;
    }
    .modern-search-form {
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
    .modern-table thead th, .modern-table td {
        font-size: 0.95rem;
        padding: 10px 6px;
    }
    .modern-meeting-details {
        flex-direction: column;
        gap: 6px;
    }
}
@media (max-width: 600px) {
    .modern-container {
        padding: 10px 2px;
    }
    .modern-info-bar h1 {
        font-size: 1.2rem;
    }
    .modern-table-wrapper {
        margin-top: 8px;
    }
    .modern-table thead th, .modern-table td {
        font-size: 0.85rem;
        padding: 8px 2px;
    }
    .modern-meeting-details {
        font-size: 0.98rem;
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

