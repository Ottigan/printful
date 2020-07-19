<?php
session_start();
// EDIT or DELETE the current task
if (isset($_POST['edit-task']) && !empty($_POST['title'])) {
    require '../auth_logic/db.php';

    $task = $_POST['title'];
    $description = $_POST['taskDescription'];
    $user = $_SESSION['id'];
    $taskID = $_POST['edit-task'];

    $stmt = mysqli_stmt_init($conn);
    $sql = "UPDATE tasks SET task = ?, taskDescription = ? WHERE user = ? AND id = ?";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../todo.php');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'ssii', $task, $description, $user, $taskID);
        mysqli_stmt_execute($stmt);

        header('location: ../todo.php');
        exit();
    }
} else if (isset($_POST['delete-task'])) {
    require '../auth_logic/db.php';

    $user = $_SESSION['id'];
    $taskID = $_POST['delete-task'];

    $stmt = mysqli_stmt_init($conn);
    $sql = "DELETE FROM tasks WHERE user = ? AND id = ?";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../todo.php');
    } else {
        mysqli_stmt_bind_param($stmt, 'ii', $user, $taskID);
        mysqli_stmt_execute($stmt);

        header('location: ../todo.php');
        exit();
    }
} else {
    header('location: ../todo.php');
    exit();
}
