<!DOCTYPE html>
<html>
<head>
    <title> Task Assignment </title>
</head>
<body>
    <h2>Labore Start his work</h2>

    <h3 style="color: green;"> {{ $assignment->farm_labore->user->name }} has completed his work {{ $assignment->task->title }} assigned at the {{ $assignment->assigned_date }} pls verify his work</h3>

    <h4 style="color: red;">For further information contact the farm labore : {{ $assignment->farm_labore->user->phone_number }} </h4>


</table>

    
</body>
</html>