<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Coursework 2 Database Quiz Application</h1>
        <h1>You are logged in as STAFF: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
    </div>
    <p>
        <a href="createquiz.php" class="btn btn-primary" style='font-size:125%;'><b>Create quizzes</b></a>
    </p>
    <p>
        <a href="editquiz.php" class="btn btn-primary" style='font-size:125%;'><b>Edit quizzes and questions</b></a>
    </p>
    <p>
        <a href="deletequiz.php" class="btn btn-primary" style='font-size:125%;'><b>Delete quizzes</b></a>
    </p>
    <br>
    <p>
        <a href="failed.php" class="btn btn-primary" style='font-size:110%;'><b>See failed quizzes attempts from all students</b></a>
    </p>
    <p>
        <a href="deleted_log.php" class="btn btn-primary" style='font-size:110%;'><b>See deleted quizzes</b></a>
    </p>
    <br>
    <p style='font-size:125%;'> Sign out to change how you are logged in </p>
    <br>
    <p>
        <a href="logout.php" class="btn btn-danger" style='font-size:115%;'>Sign Out</a>
    </p>
</body>
</html>