<?php
session_start();
// Add new Task to DB
if (isset($_POST['add-task']) && !empty($_POST['title'])) {
    require '../auth_logic/db.php';

    $task = $_POST['title'];
    $description = $_POST['taskDescription'];
    $created = date("d M");
    $boolean = 0;
    $user = $_SESSION['id'];

    $stmt = mysqli_stmt_init($conn);
    $sql = "INSERT INTO tasks (task, taskDescription, dateWhen, status, user)
             VALUES (?, ?, ?, ?, ?) ";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../todo.php');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'sssii', $task, $description, $created, $boolean, $user);
        mysqli_stmt_execute($stmt);

        header('location: ../todo.php');
        exit();
    }
} else {
    header('location: ../todo.php');
    exit();
}
