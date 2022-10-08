<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


// Include config file
require_once "config.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>quizzes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 15px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Coursework 2 Database Quiz Application</h1>
        <h1>You are logged in as STAFF: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
        <br>
        <h4>The FAILED quizzes attempts for all Students are here:</h4>
        <h4>This page is to test the SQL Stored Procedure in which it selects all the students with score smaller than 40%</h4>
    </div>
    <p style="font-size:+150%;"> The failed attempts are listed below:</p>
    <?php
    
    $sql_procedure = "CALL failingstudents";
    $result_procedure = mysqli_query($link, $sql_procedure);
    while($row_procedure = mysqli_fetch_array($result_procedure)){
        $current_username = $row_procedure['username'];
        $current_score = $row_procedure['Score'];
        $current_max_score = $row_procedure['Max_Score'];
        $current_quiz_id = $row_procedure['Quiz_ID'];
        $percentage = ($current_score / $current_max_score) * 100;

        echo "<p style='font-size:+150%;'><b>".$current_username."</b> took ".$percentage."% (".$current_score."/".$current_max_score.") in Quiz Number: ".$current_quiz_id."<p>";
    }


    mysqli_close($link);
    ?>
    <br>
    <br>
    <p>
        <a href="main_staff.php" class="btn btn-warning">Back to main page</a>
        <br>
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </p>
</body>
</html>