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
        <h2>Deleted Quiz Logs are here:</h2>
    </div>
    <?php
    $temp_user = $_SESSION["id"];
    $sql_set_user = "SET @staff = '$temp_user'";
    $result_set_user = mysqli_query($link, $sql_set_user);

    $sql = "SELECT * FROM Quiz_Deleted_Log";
    $result = mysqli_query($link, $sql);
    while($row = mysqli_fetch_array($result)){
        $staff_id = $row['Staff_ID'];
        $quiz_id = $row['Quiz_ID'];
        $date_time = $row['Date_Time'];

        echo "<p style='font-size:+150%;'> The Staff ID: ".$staff_id." deleted the Quiz ID: ".$quiz_id." at the date and time: ".$date_time."</p>";
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