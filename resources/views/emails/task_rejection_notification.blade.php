<!DOCTYPE html>
<html>
<head>
    <title> Task Rejection</title>
</head>
<body>
    <h2>Task Rejection Notification</h2>

    <h3 style="color: red;">Farm Labore {{ $assignment->farm_labore->user->name }} has rejected  work {{ $assignment->task->title }} assigned at the {{ $assignment->assigned_date }} due to {{ $assignment->rejected_reason }}</h3>

    <h4 style="color: red;">For further information contact the farm labore : {{ $assignment->farm_labore->user->phone_number }} </h4>

    <p>Task Assignment ID : {{ $assignment->id }}</p>

</table>

    
</body>
</html>