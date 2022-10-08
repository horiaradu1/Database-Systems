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

// Include existing questions

$n = 0;
$sql_existing_question = "SELECT Question_ID FROM Quiz_Question WHERE Quiz_ID = '$quiz_id'";
$result_question = mysqli_query($link, $sql_existing_question);
while($row_question = mysqli_fetch_array($result_question)){
    $existing_question_id = $row_question['Question_ID'];
    $sql_existing_question_string = "SELECT Question_String FROM Question WHERE Question_ID = '$existing_question_id'";
    $result_question_string = mysqli_query($link, $sql_existing_question_string);
    $row_question_string = mysqli_fetch_array($result_question_string);
    $question_strings[$n] = $row_question_string['Question_String'];
    $m = 0;

    $sql_existing_answers = "SELECT Answer_ID FROM Question_Answers WHERE Question_ID = '$existing_question_id'";
    $result_answers = mysqli_query($link, $sql_existing_answers);
    while($row_answers = mysqli_fetch_array($result_answers)){
        $existing_answer_id = $row_answers['Answer_ID'];
        $sql_existing_answer_string = "SELECT Answer_String FROM Answers WHERE Answer_ID = '$existing_answer_id'";
        $result_answer_string = mysqli_query($link, $sql_existing_answer_string);
        $row_answer_string = mysqli_fetch_array($result_answer_string);
        $answer_strings[$n][$m] = $row_answer_string['Answer_String'];
        $m = $m + 1;
    }
    $n = $n + 1;
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $score = 0;
    $max_score = 0;
    for($i = 0; $i < $n; $i++){
        if(isset($_POST["$i"])){
            $checked_answers = $_POST["$i"];
            $sql_checked_answers = "SELECT Answer_ID FROM Answers WHERE Answer_String = '$checked_answers'";
            $result_checked_answers = mysqli_query($link, $sql_checked_answers);
            $row_checked_answers = mysqli_fetch_array($result_checked_answers);
            $checked_answers_id = $row_checked_answers['Answer_ID'];
            $sql_check_correct = "SELECT Is_Correct FROM Question_Answers WHERE Answer_ID = '$checked_answers_id'";
            $result_check_correct = mysqli_query($link, $sql_check_correct);
            $row_check_correct = mysqli_fetch_array($result_check_correct);
            $correct_final = $row_check_correct['Is_Correct'];
            if($correct_final == 1){
                $score = $score + 1;
            }
        }
        $max_score = $max_score + 1;
    }

    $user_id = trim($_SESSION["id"]);
    $quiz_id = trim($_SESSION["quiz_id"]);
    $current_date = date("Y-m-d");

    $sql_attempts = "INSERT INTO Quiz_Student (Student_ID, Quiz_ID, Score, Max_Score, Date_of_Attempt) VALUES ('$user_id', '$quiz_id', '$score', '$max_score', '$current_date')";
    $result_attempts = mysqli_query($link, $sql_attempts);

    $_SESSION["score"] = $score;
    $_SESSION["max_score"] = $max_score;
    echo $score;
    mysqli_close($link);
    header("location: results.php");
}

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
        <h2>You are taking quiz number: <b><?php echo htmlspecialchars($_SESSION["quiz_id"]); ?></b></h2>
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
    
        <?php
            for($x = 0; $x < $n; $x++){
                shuffle($answer_strings[$x]); /* Shuffle the answers so that the first one is not always the correct one */
                $counter = $x+1;
                echo "<div class='col'>";
                    echo "<div class='row'>";
                        echo "<label for='question'><b><font size='+1'> Question ".$counter.": </b>" . $question_strings[$x] . "</font></label>";
                        //echo " <input type='radio' name='question[]' class='form-group' style='width:25%;' value=''>";
                    echo "</div>";
                    echo "<div class='row'>";
                        echo "<input type='radio' name='".$x."' class='form-check' value='".$answer_strings[$x][0]."'> ".$answer_strings[$x][0]." </input>";
                        //echo " <label for='answer'>  " . $answer_strings[$x][0] . "</label>";
                    echo "</div>";
                    echo "<div class='row'>";
                        echo "<input type='radio' name='".$x."' class='form-check' value='".$answer_strings[$x][1]."'> ".$answer_strings[$x][1]." </input>";
                        //echo " <label for='answer'>  " . $answer_strings[$x][1] . "</label>";
                    echo "</div>";
                    echo "<div class='row'>";
                        echo "<input type='radio' name='".$x."' class='form-check' value='".$answer_strings[$x][2]."'> ".$answer_strings[$x][2]." </input>";
                        //echo " <label for='answer'>  " . $answer_strings[$x][2] . "</label>";
                    echo "</div>";
                    echo "<div class='row'>";
                        echo "<input type='radio' name='".$x."' class='form-check' value='".$answer_strings[$x][3]."'> ".$answer_strings[$x][3]." </input>";
                        //echo " <label for='answer'>  " . $answer_strings[$x][3] . "</label>";
                    echo "</div>";
                echo "</div>";
                echo "<br>";
            }
        ?>

        <br>
        <input type="submit" class="btn btn-success" value="Submit quiz" style='width:10%;font-size:125%;'>
    </form>
    <br>
    <p>
        <!-- <a href="quizzes.php" class="btn btn-warning">Back</a> -->
        
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </p>
    <script type='text/javascript'>
            function setInterval(() => {
                
            }, 1000);
    </script>
</body>
</html>