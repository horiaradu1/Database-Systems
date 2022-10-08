<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

//Define variables
$quiz_id = "";
$quiz_name = "";
$quiz_available = "";
$quiz_duration = "";
$quiz_err = "";
$quiz_id_err = "";
$quiz_status = "";
// Include config file
require_once "config.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["quiz_id"])) | empty(trim($_POST["quiz_name"])) | empty(trim($_POST["quiz_duration"]))){
        $quiz_err = "Please enter all the needed info.";
        $quiz_id_err = "Please enter all the needed info.";
    }elseif((trim($_POST["quiz_available"]) != "0") && (trim($_POST["quiz_available"]) != "1")){
        $quiz_err = "Enter 1 or 0 for available.";
    }elseif(trim($_POST["quiz_duration"]) == 0 | trim($_POST["quiz_duration"]) == "0"){
        $quiz_err = "Can not enter 0 for duration";
    }else{
        // Prepare a select statement
        $id_test = $_POST["quiz_id"];
        $sql = "SELECT Quiz_ID FROM Quiz WHERE Quiz_ID = '$id_test' ";
        $result = mysqli_query($link, $sql);
        if(mysqli_num_rows($result) >0){
            $quiz_id_err = "Quiz ID already exists, try another one.";
            $quiz_err = "";
        }else{
            $quiz_id = $id_test;
        }
        
    }

    if(empty($quiz_err) && empty($quiz_id_err)){
        $sql_insert = "INSERT INTO Quiz (Quiz_ID, Quiz_Name, Quiz_Author_ID, Quiz_Available, Quiz_Duration) VALUES (?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql_insert)){
        mysqli_stmt_bind_param($stmt, "isiii", $param_quizid, $param_quizname, $param_quizauthorid, $param_quizavailable, $param_quizduration);
        
        $quiz_name = trim($_POST["quiz_name"]);
        $quiz_available = trim($_POST["quiz_available"]);
        $quiz_duration = trim($_POST["quiz_duration"]);

        $param_quizid = $quiz_id;
        $param_quizname = $quiz_name;
        $param_quizauthorid = $_SESSION["id"];
        $param_quizavailable = $quiz_available;
        $param_quizduration = $quiz_duration;

        mysqli_stmt_execute($stmt);
        $quiz_status = "Quiz created succesfully,";
        mysqli_stmt_close($stmt);
        }
    
    }
    // Close connection
    mysqli_close($link);
}

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
        <h2>Here you can CREATE a quiz</h2>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <p>
        <div class="form-group <?php echo (!empty($quiz_err)) ? 'has-error' : ''; ?>">
            <label>Quiz ID: </label>
            <input type="number" min="1" name="quiz_id" class="form-check" value="<?php echo $quiz_id; ?>">
            <span class="help-block"><?php echo $quiz_id_err; ?></span>
            <label>Quiz Name: </label>
            <input type="text" name="quiz_name" class="form-check" value="<?php echo $quiz_name; ?>">
            <span class="help-block"><?php echo $quiz_err; ?></span>
            <label>Quiz Available </label>
            <label>(type 1 for available and 0 for unavailable):</label>
            <input type="number" min="0" max="1"  name="quiz_available" class="form-check" value="<?php echo $quiz_available; ?>">
            <span class="help-block"><?php echo $quiz_err; ?></span>
            <label>Quiz Duration (minutes): </label>
            <input type="number" name="quiz_duration" class="form-check" value="<?php echo $quiz_duration; ?>">
            <span class="help-block"><?php echo $quiz_err; ?></span>
        </div>
    </p>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Create Quiz" style='font-size:125%;'>
            <span class="help-block"><?php echo $quiz_status; ?></span>
        </div>
    </form>
    <p>
        <a href="main_staff.php" class="btn btn-warning">Back to main page</a>
        <br>
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </p>
</body>
</html>