<?php
// Initialize the session
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Define variables
$quiz_id = "";
$quiz_err = "";
$quiz_delete_validity = "";

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
            $quiz_id = trim($_POST["quiz_id"]);

            $temp_user = $_SESSION["id"];
            $sql_set_user = "SET @staff = '$temp_user'";
            $result_set_user = mysqli_query($link, $sql_set_user);
        

            $sql_select_question_id = "SELECT Question_ID FROM Quiz_Question WHERE Quiz_ID = '$quiz_id'";
            $result = mysqli_query($link, $sql_select_question_id);
            if(is_bool(mysqli_fetch_array($result))){
                while($row = mysqli_fetch_array($result)){
                    $current_question_id = $row['Question_ID'];
                    $sql_delete_answers = "DELETE FROM Question_Answers WHERE Question_ID = ('$current_question_id')";
                    $result = mysqli_query($link, $sql_delete_answers);
                }
            }
            $sql_delete_questions = "DELETE FROM Quiz_Question WHERE Quiz_ID = ('$quiz_id')";
            $result = mysqli_query($link, $sql_delete_questions);

            
            $sql_delete_quiz = "DELETE FROM Quiz WHERE Quiz_ID = '$quiz_id'";
            $result = mysqli_query($link, $sql_delete_quiz);

            $quiz_delete_validity = "Quiz deleted succesfully";

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
        <h2>Below are all the available quizzes to DELETE</h2>
        <br>
        <h4>Enter the number of the quiz you want to DELETE in the box below</h4>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <p>
        <div class="form-group <?php echo (!empty($quiz_err)) ? 'has-error' : ''; ?>">
            <label>Quiz number: </label>
            <input type="number" min="1" name="quiz_id" class="form-check" value="<?php echo $quiz_id; ?>">
            <span class="help-block"><?php echo $quiz_err; ?></span>
            <br>
            <input type="submit" class="btn btn-primary" value="Delete quiz" style='font-size:125%;'>
            <span class="help-block"><?php echo $quiz_delete_validity; ?></span>
        </div>
    </p>
    </form>
    <p><b>All quizzes are listed below:</b></p>
    <?php
    $query = "SELECT * FROM Quiz";
    $result = mysqli_query($link, $query);

    echo "<table>";

    while($row_quiz = mysqli_fetch_array($result)){   // Creates a loop to loop through results
        if($row_quiz['Quiz_Available'] == 1){
            $available_string = "Yes";
        }else{
            $available_string = "No";
        }

        $query_author = "SELECT username FROM users WHERE id = ?";
        $stmt_author = mysqli_prepare($link, $query_author);
        mysqli_stmt_bind_param($stmt_author, "s", $author_id);
        $author_id = $row_quiz['Quiz_Author_ID'];
        mysqli_stmt_execute($stmt_author);
        mysqli_stmt_store_result($stmt_author);
        mysqli_stmt_bind_result($stmt_author, $author_name);
        mysqli_stmt_fetch($stmt_author);

        echo "<font size='+1'><b>QUIZ: </font> </b> | Number: <b>" . $row_quiz['Quiz_ID'] . "</b> | Name: " . $row_quiz['Quiz_Name'] . " | Author: " . $author_name . " | Available: " . $available_string . " | Duration: " . $row_quiz['Quiz_Duration'] . " minutes |<br>";
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