<p>Hello {{ $attendee->name }},</p>
<p>Your meeting code for {{ $attendee->booking->meetingRoom->name ?? 'the meeting' }} is:</p>
<h2>{{ $attendee->meeting_code }}</h2>
<p>Please use this code to verify your presence at the meeting.</p>
<p>Best regards,<br>CocoaSpace Team</p>