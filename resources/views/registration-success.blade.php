<!DOCTYPE html>
<html>
<head>
    <title>Registration Success</title>
</head>
<body>
    <h1>Attendee Registered Successfully!</h1>

    <p>An email has been sent to your email address.</p>
    <p>Your phone’s messaging app will open shortly to send a confirmation SMS.</p>

    <script>
        const phone = @json($phone);
        const message = @json($message);

        // Open SMS app with pre-filled message
        const smsUrl = `sms:${phone}?body=${encodeURIComponent(message)}`;
        window.location.href = smsUrl;
    </script>

    <p>If the SMS app does not open automatically, <a href="sms:{{ $phone }}?body={{ urlencode($message) }}">click here to send it manually</a>.</p>

    <p><a href="{{ route('register.attendees.register', $booking_id) }}">Back to Registration</a></p>
</body>
</html>
