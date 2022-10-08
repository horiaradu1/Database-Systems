<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Define variables
$quiz_id = "";
$quiz_err = "";

// Include config file
require_once "config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["quiz_id"]))){
        $quiz_err = "Please enter a number of one of the current quizzes: ";
    }else{
        $id_test = $_POST["quiz_id"];
        $sql = "SELECT Quiz_ID FROM Quiz WHERE Quiz_ID = '$id_test' ";
        $result = mysqli_query($link, $sql);
        if(mysqli_num_rows($result) < 1){
            $quiz_err = "This quiz is does not exist";
        }else{
            $quiz_id = $id_test;
            $_SESSION["quiz_id"] = $quiz_id;
            header("location: editing.php");
        }
    }
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
        <h2>Below are all the available quizzes to EDIT</h2>
        <br>
        <h4>Enter the number of the quiz you want to EDIT in the box below</h4>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <p>
        <div class="form-group <?php echo (!empty($quiz_err)) ? 'has-error' : ''; ?>">
            <label>Quiz number: </label>
            <input type="number" min="1" name="quiz_id" class="form-check" value="<?php echo $quiz_id; ?>">
            <span class="help-block"><?php echo $quiz_err; ?></span>
            <br>
            <input type="submit" class="btn btn-primary" value="Go to edit quiz" style='font-size:125%;'>
        </div>
    </p>
    </form>
    <p><b>All quizzes are listed below:</b></p>
    <?php
    $query = "SELECT * FROM Quiz";
    $result_all = mysqli_query($link, $query);

    echo "<table>";

    while($row = mysqli_fetch_array($result_all)){   // Creates a loop to loop through results
        if($row['Quiz_Available'] == 1){
            $available_string = "Yes";
        }else{
            $available_string = "No";
        }

        $query_author = "SELECT username FROM users WHERE id = ?";
        $stmt_author = mysqli_prepare($link, $query_author);
        mysqli_stmt_bind_param($stmt_author, "s", $author_id);
        $author_id = $row['Quiz_Author_ID'];
        mysqli_stmt_execute($stmt_author);
        mysqli_stmt_store_result($stmt_author);
        mysqli_stmt_bind_result($stmt_author, $author_name);
        mysqli_stmt_fetch($stmt_author);

        echo "<font size='+1'><b>QUIZ: </font> </b> | Number: <b>" . $row['Quiz_ID'] . "</b> | Name: " . $row['Quiz_Name'] . " | Author: " . $author_name . " | Available: " . $available_string . " | Duration: " . $row['Quiz_Duration'] . " minutes |<br>";
        mysqli_stmt_close($stmt_author);
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