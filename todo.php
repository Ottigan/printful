<?php
// Limiting access to logged in users by fetching 
// the global $_SESSION variable
session_start();
if (!isset($_SESSION['id'])) {
    header('location: index.php');
    exit();
} else {
    require 'auth_logic/db.php';

    // Fetch all the current tasks for the currently logged in USER
    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT * FROM tasks WHERE user = ?';
    $tasks;

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('location: profile.php');
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
        mysqli_stmt_execute($stmt);
        $tasks = mysqli_stmt_get_result($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link rel="shortcut icon" href="assets/favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/todo.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <!-- Correctly ending users SESSION -->
        <form action="auth_logic/logout.php" method="POST">
            <!-- Personalizwed header based on currentyl logged in users chosen name -->
            <?php
            echo "<h4>Welcome, $_SESSION[name]</h4>";
            ?><button class="log-out-btn button" type="submit">Log Out</button>
        </form>
    </header>
    <main>
        <h1>To Do List</h1>
        <!-- Populating document with current tasks -->
        <?php echo "<div class='container'>"; ?>
        <!-- Checking if user has any tasks -->
        <!-- If the there are none, present "Nothing added" -->
        <?php if (mysqli_num_rows($tasks)) { ?>
            <?php while ($row = mysqli_fetch_array($tasks)) { ?>
                <?php echo "   
                    <div class='task-container' id='task-container-$row[id]'>
                    <div class='title-and-about'> " ?>
                <!-- Adjusting checkbox status based on info in DB -->
                <?php if ($row['status'] === 1) { ?>
                    <?php
                    echo "<span>
                        <input id='$row[id]' class='status' type='checkbox' checked>
                        <h3 id='title-$row[id]'>$row[task]</h3>
                    </span>";
                    ?>
                <?php } else { ?>
                    <?php
                    echo "<span>
                        <input id='$row[id]' class='status' type='checkbox'>
                        <h3 id='title-$row[id]'>$row[task]</h3>
                    </span>";
                    ?>
                <?php } ?>
                <!-- If there is no description element is not created -->
                <?php if ($row['taskDescription']) { ?>
                    <?php echo "<p id='description-$row[id]'>$row[taskDescription]</p> " ?>
                <?php } ?>
                <?php echo "
                    </div>
                    <div class='when-and-edit'>
                    <p id='when-$row[id]' class='created-when'>$row[dateWhen]</p>
                    <button id='edit-$row[id]' class='edit-btn button' type='button'>Edit</button>
                    </div>
                    </div> " ?>
            <?php }
            echo "<button class='button add-btn' type='button'>Add</button>"; ?>
        <?php } else {
            echo "<h2>nothing...</h2><button class='button add-btn first-add' type='button'>Add</button>";
        } ?>
        <?php echo "</div>"; ?>
        <div id="add-task-container" class="hidden-block">
            <h3>New Task</h3>
            <form action="todo_logic/add_task.php" method="POST">
                <div>
                    <label for="title">Title</label><br>
                    <input id="title" name="title" type="text" maxlength="50" required>
                </div>
                <div>
                    <label for="taskDescription">Description</label><br>
                    <textarea name="taskDescription" id="taskDescription"></textarea>
                </div>
                <div>
                    <button id="add-back-btn" type="button" class="button back-btn">Back</button>
                    <button name="add-task" type="submit" class="button add-submit-btn">Add</button>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <h5>ALL RIGHTS RESERVED "Printful" 2020.</h5>
    </footer>
    <script src="js/todo.js"></script>
</body>

</html>