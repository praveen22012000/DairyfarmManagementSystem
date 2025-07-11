<!DOCTYPE html>
<html>
<head>
    <title> Task Approval </title>
</head>
<body>
    <h2>Task Completion Approval Notification</h2>

    <h3 style="color: green;">Hi {{ $assignment->farm_labore->user->name }},You have successfully scompleted your work {{ $assignment->task->title }} assigned at the {{ $assignment->assigned_date }}</h3>

    <h4 style="color: red;">For further information contact the farm labore : {{ $assignment->farm_labore->user->phone_number }} </h4>


</table>

    
</body>
</html>