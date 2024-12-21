<?php
// Load existing tasks
$tasksFile = 'tasks.json';
$tasks = json_decode(file_get_contents($tasksFile), true) ?? [];

// Handle Add Task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $newTask = htmlspecialchars(trim($_POST['task']));
    if (!empty($newTask)) {
        $tasks[] = ['task' => $newTask, 'done' => false];
        file_put_contents($tasksFile, json_encode($tasks));
    }
    header('Location: index.php');
    exit;
}

// Handle Mark Done/Undone
if (isset($_GET['mark'])) {
    $index = (int)$_GET['mark'];
    $tasks[$index]['done'] = !$tasks[$index]['done'];
    file_put_contents($tasksFile, json_encode($tasks));
    header('Location: index.php');
    exit;
}

// Handle Delete Task
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    array_splice($tasks, $index, 1);
    file_put_contents($tasksFile, json_encode($tasks));
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>
        <form method="POST">
            <input type="text" name="task" placeholder="New Task" required>
            <button type="submit">Add Task</button>
        </form>
        <ul>
            <?php foreach ($tasks as $index => $task): ?>
                <li>
                    <span style="<?= $task['done'] ? 'text-decoration: line-through;' : '' ?>">
                        <?= htmlspecialchars($task['task']) ?>
                    </span>
                    <a href="?mark=<?= $index ?>">Mark as <?= $task['done'] ? 'Undone' : 'Done' ?></a>
                    <a href="?delete=<?= $index ?>">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
