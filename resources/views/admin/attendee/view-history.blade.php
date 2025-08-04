@extends('layouts.admin-sidebar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Meeting Attendees - {{ $bookingHistory->requester }}</h4>
                    <p class="text-muted">
                        <strong>Date:</strong> {{ $bookingHistory->date }} | 
                        <strong>Time:</strong> {{ $bookingHistory->time }} | 
                        <strong>Room:</strong> {{ $bookingHistory->meetingRoom->name ?? 'N/A' }}
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attendees.view-history', $bookingHistory->id) }}" method="GET" class="modern-search-form">
                        <div class="input-group mb-3">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search attendees by name, email, or phone...">
                            <button class="btn btn-outline-secondary" type="submit">🔍 Search</button>
                            <a href="{{ route('admin.attendees.view-history', $bookingHistory->id) }}" class="modern-clear-btn">Clear</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendees as $attendee)
                                    <tr>
                                        <td>{{ $attendee->name }}</td>
                                        <td>{{ $attendee->email }}</td>
                                        <td>{{ $attendee->phone }}</td>
                                        <td>{{ $attendee->department }}</td>
                                        <td>{{ $attendee->gender }}</td>
                                        <td>
                                            <span class="badge bg-{{ $attendee->status == 'present' ? 'success' : 'warning' }}">
                                                {{ ucfirst($attendee->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendee->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No attendees found for this meeting.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $attendees->firstItem() ?? 0 }} to {{ $attendees->lastItem() ?? 0 }} 
                                of {{ $attendees->total() }} attendees
                            </p>
                        </div>
                        <div>
                            {{ $attendees->links() }}
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.bookings.history') }}" class="btn btn-secondary">
                            ← Back to Booking History
                        </a>
                        <div>
                            <a href="{{ route('admin.attendees.export-excel', $bookingHistory->id) }}" class="btn btn-success me-2">
                                📊 Export CSV
                            </a>
                            <a href="{{ route('admin.attendees.export-pdf', $bookingHistory->id) }}" class="btn btn-danger">
                                📄 Export HTML
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-search-form {
    margin-bottom: 20px;
}

.modern-clear-btn {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    margin-left: 10px;
}

.modern-clear-btn:hover {
    background-color: #5a6268;
    color: white;
    text-decoration: none;
}

.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background-color: #42CCC5;
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 20px;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.8em;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}
</style>
@endsection 