<?php

// Validation during SIGN UP attempt
if (isset($_POST['sign-up-btn'])) {
    require 'db.php';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    // Error if any fields is empty
    if (empty($name) || empty($email) || empty($pwd)) {
        header('location: ../index.php?error=emptyfields&name=' . $name . '&email=' . $email);
        exit();
        // Error if name and email have incorrect format
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9]*$/', $name)) {
        header('location: ../index.php?error=invalidnameemail');
        exit();
        // Error if email has incorrect format
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: ../index.php?error=invalidemail&name=' . $name);
        exit();
        // Error if name has incorrect format
    } else if (!preg_match('/^[a-zA-Z0-9]*$/', $name)) {
        header('location: ../index.php?error=invalidname&email=' . $email);
        exit();
    } else {
        $stmt = mysqli_stmt_init($conn);
        $sql = 'SELECT emailUsers FROM users WHERE emailUsers = ?';

        // Return user to index.php if stmt_prepare failed
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('location: ../index.php?error=sqlerror&name=' . $name . '&email=' . $email);
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);

            // If user with the provided email exists return an error
            if ($resultCheck > 0) {
                header('location: ../index.php?error=emailtaken&name=' . $name . '&email=' . $email);
                exit();
            } else {
                $sql = 'INSERT INTO users (nameUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)';
                $stmt = mysqli_stmt_init($conn);

                // Return user to index.php if stmt_prepare failed
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header('location: ../index.php?error=sqlerror&email=' . $email);
                    exit();
                } else {

                    // Hashing the users provided password before storing it in the DB
                    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);


                    // Adding user to DB "users" table
                    // Returning user to index.php LOGIN screen with SUCCESS message
                    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hashedPwd);
                    mysqli_stmt_execute($stmt);
                    header('location: ../index.php?signup=success');
                    exit();
                }
            }
        }
    }
} else {
    header('location: ../index.php');
    exit();
}
