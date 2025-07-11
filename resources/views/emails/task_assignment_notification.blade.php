<!DOCTYPE html>
<html>
<head>
    <title> Task Assignment </title>
</head>
<body>
    <h2>New Task Assignment</h2>

    <h3 style="color: green;">Hi {{ $task_assignment->farm_labore->user->name }},You have beed assigned for the {{ $task_assignment->task->title }} and you should complete this task within the {{ $task_assignment->due_date }}</h3>

    <h2 style="color: red;">If you have any issues pls contact the farm management</h2>

</table>

    
</body>
</html>