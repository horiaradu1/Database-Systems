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
        <h1>You are logged in as STUDENT: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
        <br>
        <h4>The TAKEN quizzes are here:</h4>
    </div>
    <p style="font-size:+150%;">All of your attempts as <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> are listed below:</p>
    <?php
    $user_id = trim($_SESSION["id"]);
    $sql_attempt = "SELECT DISTINCT * FROM Quiz_Student WHERE Student_ID = '$user_id' ORDER BY Score/Max_Score DESC";
    $result = mysqli_query($link, $sql_attempt);
    while($row = mysqli_fetch_array($result)){

        $sql_username = "SELECT username FROM users WHERE id = '$user_id'";
        $result_username = mysqli_query($link, $sql_username);
        $row_username = mysqli_fetch_array($result_username);
        $quiz_username = $row_username['username'];

        $quiz_id = $row['Quiz_ID'];
        $sql_author = "SELECT Quiz.Quiz_Name, users.username FROM Quiz INNER JOIN users ON Quiz.Quiz_Author_ID = users.id WHERE Quiz.Quiz_ID = '$quiz_id'";
        $result_author = mysqli_query($link, $sql_author);
        $row_author = mysqli_fetch_array($result_author);

        $quiz_name = $row_author['Quiz_Name'];
        $quiz_author = $row_author['username'];

        $score = $row['Score'];
        $max_score = $row['Max_Score'];
        $percentage = ($score / $max_score) * 100;
        $attempt_date = $row['Date_of_Attempt'];
        $attempt_date_display = date("d-m-Y", strtotime($attempt_date));
        
        echo "<font size='+3'> Quiz:</font><font size='+2'> <b>".$quiz_id. " - " .$quiz_name."</b>, made by ".$quiz_author." | Score: <b>".$percentage."% (".$score."/".$max_score.")</b> | Date: ".$attempt_date_display."</font><br>";
    }
    


    mysqli_close($link);
    ?>
    <br>
    <br>
    <p>
        <a href="main_student.php" class="btn btn-warning">Back to main page</a>
        <br>
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </p>
</body>
</html>