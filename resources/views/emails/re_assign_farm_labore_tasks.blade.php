<!DOCTYPE html>
<html>
<head>
    <title> Task Assignment </title>
</head>
<body>
    <h2>New Task Assignment</h2>

    <h3 style="color: green;">Hi {{ $taskassignment->farm_labore->user->name }},You have beed assigned for the {{ $taskassignment->task->title }} and you should complete this task within the {{ $taskassignment->due_date }}</h3>

    <h2 style="color: red;">If you have any issues pls contact the farm management</h2>

</table>

    
</body>
</html>