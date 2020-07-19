<?php
session_start();
// Update status in DB
if (isset($_GET['status'])) {
    require '../auth_logic/db.php';

    $status = $_GET['status'];
    $taskID = $_GET['id'];
    $user = $_SESSION['id'];

    $stmt = mysqli_stmt_init($conn);
    $sql = 'UPDATE tasks SET status = ? WHERE id = ? AND user = ?';

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: ../todo.php');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'iii', $status, $taskID, $user);
        mysqli_stmt_execute($stmt);

        header('location: ../todo.php');
        exit();
    }
} else {
    header('location: ../todo.php');
    exit();
}
