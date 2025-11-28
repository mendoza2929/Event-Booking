<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; }
        .highlight { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello {{ $name }}!</h1>
        <p>Congratulations! Your booking has been <strong>confirmed</strong>.</p>

        <hr>

        <p><strong>Event:</strong> <span class="highlight">{{ $event_title }}</span></p>
        <p><strong>Quantity:</strong> {{ $quantity }} ticket(s)</p>
        <p><strong>Total Paid:</strong> RM {{ $total }}</p>
        <p><strong>Event Date:</strong> {{ $date }}</p>

        <hr>

        <p>Your tickets are ready! We can't wait to see you there.</p>
        <p>Thank you for booking with us!</p>

        <small>This is an automated email. Please do not reply.</small>
    </div>
</body>
</html>