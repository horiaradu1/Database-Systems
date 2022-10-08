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

// Define variables
$quiz_id = trim($_SESSION["quiz_id"]);

$query = "SELECT * FROM Quiz WHERE Quiz_ID = '$quiz_id'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);

$quiz_name = $row['Quiz_Name'];
$quiz_available = $row['Quiz_Available'];
$quiz_duration = $row['Quiz_Duration'];
$quiz_available_err = "";
$quiz_err = "";

$temp = $row['Quiz_Author_ID'];
$query_author = "SELECT username FROM users WHERE id = '$temp'";
$result_author = mysqli_query($link, $query_author);
$row_author = mysqli_fetch_array($result_author);
$quiz_author = $row_author['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>quizzes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 17px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Coursework 2 Database Quiz Application</h1>
        <h1>You are logged in as STUDENT: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
        <h2>You have taken quiz number: <b><?php echo htmlspecialchars($_SESSION["quiz_id"]); ?></b></h2>
        <br>
        <h3>
        <?php
            if($quiz_available == 1){
                $available_txt = "YES";
            }else{
                $available_txt = "NO";
            }
            echo "<font size='+2'><b>QUIZ: </font> </b> | Number: <b>" . $quiz_id . "</b> | Name: " . $quiz_name . " | Author: " . $quiz_author . " | Available: " . $available_txt . " | Duration: " . $quiz_duration . " minutes |<br>";
        ?>
        </h3>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p style='font-size:130%;'> Quiz submitted! </p>
        <p style='font-size:125%;'> You have answered <?php echo $_SESSION["score"] ?> questions correctly out of <?php echo $_SESSION["max_score"] ?> in total.</p>
    </form>
    <a href="main_student.php" class="btn btn-success" style='width:10%;font-size:125%;'>Continue</a>
    
    <?php
    
    $_SESSION["quiz_id"] = "";
    $_SESSION["score"] = "";
    $_SESSION["max_score"] = "";

    ?>

</body>
</html>