<?php

// Validation during LOGIN attempt
if (isset($_POST['login-btn'])) {
    require 'db.php';

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    // Return user to index.php if any field was empty
    if (empty($email) || empty($pwd)) {
        header('location: ../index.php?error=emptyfields&email=' . $email);
        exit();
    } else {
        $stmt = mysqli_stmt_init($conn);
        $sql = 'SELECT * FROM users WHERE emailUsers = ?';

        // Return user to index.php if stmt_prepare failed
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('location: ../index.php?error=sqlerror&email=' . $email);
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Closing previous $stmt to avoid unexpected outcomes
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // If user (eamail) exist in the DB, continue validation
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($pwd, $row['pwdUsers']);

                // Checking if the provided password matches the one created at Sign Up
                // If it does, send user to their PROFILE page
                if ($pwdCheck == false) {
                    header('location: ../index.php?error=wrongpwd');
                    exit();
                } else if ($pwdCheck == true) {
                    // Start a new SESSION assigning values to the Global variable
                    session_start();
                    $_SESSION['id'] = $row['idUsers'];
                    $_SESSION['name'] = $row['nameUsers'];

                    header('location: ../todo.php?login=success');
                    exit();
                }
            } else {
                header('location: ../index.php?error=noaccount');
                exit();
            }
        }
    }
} else {
    header('location: ../index.php');
    exit();
}
