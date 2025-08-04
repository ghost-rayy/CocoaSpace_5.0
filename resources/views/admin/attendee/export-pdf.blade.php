<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Attendees Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #42CCC5;
            padding-bottom: 10px;
        }
        .meeting-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .meeting-info h3 {
            color: #42CCC5;
            margin: 0 0 10px 0;
        }
        .meeting-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #42CCC5;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-present {
            color: #28a745;
            font-weight: bold;
        }
        .status-not-present {
            color: #ffc107;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Meeting Attendees Report</h1>
        <p>Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <div class="meeting-info">
        <h3>Meeting Details</h3>
        <p><strong>Meeting Title:</strong> {{ $bookingHistory->requester }}</p>
        <p><strong>Date:</strong> {{ $bookingHistory->date }}</p>
        <p><strong>Time:</strong> {{ $bookingHistory->time }}</p>
        <p><strong>Room:</strong> {{ $bookingHistory->meetingRoom->name ?? 'N/A' }}</p>
        <p><strong>Duration:</strong> {{ $bookingHistory->duration }} hours</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
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
            @forelse($attendees as $index => $attendee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendee->name }}</td>
                    <td>{{ $attendee->email }}</td>
                    <td>{{ $attendee->phone }}</td>
                    <td>{{ $attendee->department }}</td>
                    <td>{{ $attendee->gender }}</td>
                    <td class="status-{{ $attendee->status == 'present' ? 'present' : 'not-present' }}">
                        {{ ucfirst($attendee->status) }}
                    </td>
                    <td>{{ $attendee->created_at->format('M d, Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #666;">
                        No attendees found for this meeting.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Attendees: {{ $attendees->count() }}</p>
        <p>Present: {{ $attendees->where('status', 'present')->count() }} | 
           Not Present: {{ $attendees->where('status', '!=', 'present')->count() }}</p>
    </div>
</body>
</html> 