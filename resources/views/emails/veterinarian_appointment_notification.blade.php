<!DOCTYPE html>
<html>
<head>
    <title>Appointment Notification</title>
</head>
<body>
    <h2>Veterinarian Appointment Notification</h2>

    <h3 style="color: green;">Hi {{$appointment->user->name}},You have been appointed on {{$appointment->appointment_date}} at {{ $appointment->appointment_time }}</h3>

    <h4 style="color : red;">For further information contact the farm management</h4>

  

</table>

    
</body>
</html>