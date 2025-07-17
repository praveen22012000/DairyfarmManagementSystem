<!DOCTYPE html>
<html>
<head>
    <title>Appointment Update Notification</title>
</head>
<body>
    <h2>Veterinarian Appointment Notification Update</h2>

    <h3 style="color: green;">
        Hi {{ $appointment->user->name }},<br>
        You have been scheduled for an appointment on {{ $appointment->appointment_date }} at {{ $appointment->appointment_time }}.
    </h3>

    <h4 style="color: red;">
        For further information, please contact the farm management.
    </h4>

</body>
</html>
