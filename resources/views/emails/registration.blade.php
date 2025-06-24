<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
</head>
<body>
    <h1>Registration Successful</h1>
    <p>Hello {{ $name }},</p>
    <p>Your registration was successful!</p>

    @if(!empty($meeting_code))
        <p><strong>Your Meeting Code:</strong></p>
        <h2 style="color:#42CCC5;">{{ $meeting_code }}</h2>
        <p>Please keep this code safe. You will need it to verify your attendance at the meeting.</p>
    @endif

    <br>
    <p>Best regards,<br>CocoaSpace Team</p>
</body>
</html>
