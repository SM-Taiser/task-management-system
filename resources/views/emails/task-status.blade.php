<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Notification</title>
</head>
<body>
    <h2>{{ $action === 'created' ? 'New Task Created' : 'Task Completed' }}</h2>

    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>

    <p>Status: <strong>{{ ucfirst($task->status) }}</strong></p>
    <p>Thank you for using our Task Management System.</p>
</body>
</html>
