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
$nothing = "";
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
    $existing_question_string[$n] = $row_question_string["Question_String"];
    $m = 0;

    $sql_existing_answers = "SELECT Answer_ID FROM Question_Answers WHERE Question_ID = '$existing_question_id'";
    $result_answers = mysqli_query($link, $sql_existing_answers);
    while($row_answers = mysqli_fetch_array($result_answers)){
        $existing_answer_id = $row_answers['Answer_ID'];
        $sql_existing_answer_string = "SELECT Answer_String FROM Answers WHERE Answer_ID = '$existing_answer_id'";
        $result_answer_string = mysqli_query($link, $sql_existing_answer_string);
        $row_answer_string = mysqli_fetch_array($result_answer_string);
        $existing_answer_string[$n][$m] = $row_answer_string["Answer_String"];
        $m = $m + 1;
    }
    $n = $n + 1;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["quiz_name"])) | empty(trim($_POST["quiz_duration"]))){
        $quiz_err = "Please enter all the needed info.";
    }elseif((trim($_POST["quiz_available"]) != "0") && (trim($_POST["quiz_available"]) != "1")){
        $quiz_available_err = "Enter 1 (YES) or 0 (NO) for available.";
    }else{
        if(empty($quiz_available_err) && empty($quiz_err)){
            $sql_quiz = "UPDATE Quiz SET Quiz_Name = ?, Quiz_Available = ?, Quiz_Duration = ? WHERE Quiz_ID = '$quiz_id' ";
            if($stmt_quiz = mysqli_prepare($link, $sql_quiz)){
                mysqli_stmt_bind_param($stmt_quiz, "sii", $param_name, $param_available, $param_duration);
                $quiz_name = trim($_POST["quiz_name"]);
                $quiz_available = trim($_POST["quiz_available"]);
                $quiz_duration = trim($_POST["quiz_duration"]);

                $param_name = $quiz_name;
                $param_available = $quiz_available;
                $param_duration = $quiz_duration;

                mysqli_stmt_execute($stmt_quiz);

                $sql_select_question_id = "SELECT Question_ID FROM Quiz_Question WHERE Quiz_ID = '$quiz_id'";
                $result = mysqli_query($link, $sql_select_question_id);
                $row_select_question = mysqli_fetch_array($result);

                $current_question_id = $row_select_question['Question_ID'];
                $sql_delete_answers = "DELETE FROM Question_Answers WHERE Question_ID = ('$current_question_id')";
                $result = mysqli_query($link, $sql_delete_answers);

                $sql_delete_questions = "DELETE FROM Quiz_Question WHERE Quiz_ID = ('$quiz_id')";
                $result = mysqli_query($link, $sql_delete_questions);

                for ($i = 0; $i < count($_POST["question"]); $i++){
                    if(!empty($_POST['question'][$i]) && !empty($_POST["answer"][4 * $i]) && !empty($_POST['answer'][4 * $i + 1]) && !empty($_POST['answer'][4 * $i + 2]) && !empty($_POST['answer'][4 * $i + 3])){
                        $question_string = mysqli_real_escape_string($link, $_POST['question'][$i]);
                        $answers[1] = mysqli_real_escape_string($link, $_POST['answer'][4 * $i]);
                        $answers[2] = mysqli_real_escape_string($link, $_POST['answer'][4 * $i + 1]);
                        $answers[3] = mysqli_real_escape_string($link, $_POST['answer'][4 * $i + 2]);
                        $answers[4] = mysqli_real_escape_string($link, $_POST['answer'][4 * $i + 3]);    
                        
                        $sql_quiz_question = "INSERT INTO Quiz_Question (Quiz_ID) VALUES ('$quiz_id')";
                        $result = mysqli_query($link, $sql_quiz_question);
                        $question_id = $link->insert_id;
                        $sql_question = "INSERT INTO Question (Question_ID, Question_String) VALUES ('$question_id', '$question_string')";
                        $result = mysqli_query($link, $sql_question);
                        for ($j = 1; $j < 5; $j++){
                            if ($j == 1){
                                $correct_answer = 1;
                            }else{
                                $correct_answer = 0;
                            }
                            $sql_question_answers = "INSERT INTO Question_Answers (Question_ID, Is_Correct) VALUES ('$question_id', '$correct_answer')";
                            $result = mysqli_query($link, $sql_question_answers);
                            $answer_id = $link->insert_id;
                            
                            $sql_answer = "INSERT INTO Answers (Answer_ID, Answer_String) VALUES ('$answer_id', '$answers[$j]')";
                            
                            $result = mysqli_query($link, $sql_answer);
                        }

                    }

                }
                
                $_SESSION["quiz_id"] = "";
                mysqli_stmt_close($stmt_quiz);
                mysqli_close($link);
                header("location: editquiz.php");
            }
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
        <h2>You are editing quiz number: <b><?php echo htmlspecialchars($_SESSION["quiz_id"]); ?></b></h2>
        <br>
        <h4>
        <?php
            if($quiz_available == 1){
                $available_txt = "YES";
            }else{
                $available_txt = "NO";
            }
            echo "<font size='+1'><b>QUIZ: </font> </b> | Number: <b>" . $quiz_id . "</b> | Name: " . $quiz_name . " | Author: " . $quiz_author . " | Available: " . $available_txt . " | Duration: " . $quiz_duration . " minutes |<br>";
        ?>
        </h4>
    </div>
    <p> First Answer (1) will be the <b>correct</b> Answer in the database. </p>
    <p> For a Question and its Answers to succesfully save, you must fill in all the 5 boxes of one Question. </p>
    <p> If any is left empty, the whole Question will to be added to the database, thus, this being the way you can delete Questions. </p>
    <p> Make sure you add the correct amount of Questions you want in the Quiz before editing, as pressing the "Add Question" button will reset all the fields. </p>
    <p> You also have the option to replace some data regarding the Quiz, the name, the duration and availability. </p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <p>
        <b><font size='+1'>QUIZ: </font> </b>
        <label>Name: </label>
        <input type="text" name="quiz_name" class="form-check" value="<?php echo $quiz_name; ?>">
        <label>Available: </label>
        <input type="number" min="0" max="1" name="quiz_available" class="form-check" value="<?php echo $quiz_available; ?>">
        <span class="help-block" color = "red"><?php echo $quiz_available_err; ?></span>
        <label>Duration: </label>
        <input type="number" name="quiz_duration" class="form-check" value="<?php echo $quiz_duration; ?>">
        <span class="help-block"><?php echo $quiz_err; ?></span>

    </p>
    <?php
        for($x = 0; $x < $n; $x++){
            
            echo "<div class='col'>";
                echo "<div class='row'>";
                    echo "<label for='question'><b><font size='+1'> QUESTION: </font></b></label>";
                    echo " <input type='text' name='question[]' class='form-group' style='width:25%;' value=\"".$existing_question_string[$x]."\">";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<label for='answer'>Answer 1:</label>";
                    echo " <input type='text' name='answer[]' class='form-check' placeholder='CORRECT ANSWER' value=\"".$existing_answer_string[$x][0]."\">";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<label for='answer'>Answer 2:</label>";
                    echo " <input type='text' name='answer[]' class='form-check' value=\"".$existing_answer_string[$x][1]."\">";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<label for='answer'>Answer 3:</label>";
                    echo " <input type='text' name='answer[]' class='form-check' value=\"".$existing_answer_string[$x][2]."\">";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<label for='answer'>Answer 4:</label>";
                    echo " <input type='text' name='answer[]' class='form-check' value=\"".$existing_answer_string[$x][3]."\">";
                echo "</div>";
            echo "</div>";
            echo "<br>";
        }
    ?>
        <div id='question_form'>
            <!-- Add Question button forms will go here -->
        </div>
    <br>
    <input type="submit" class="btn btn-success" value="Apply changes" style='width:10%;font-size:125%;'>
    <br>
    <br>
    <button class="btn btn-info" onclick="add_question()" type="button" style='font-size:105%;'>Add Question</button>
    </form>
    <br>
    <p>
        <a href="editquiz.php" class="btn btn-warning">Back</a>
        <br>
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </p>
    <script type='text/javascript'>
            function add_question() {
                document.getElementById("question_form").innerHTML += 
                "<br>\
                <div class='row'>\
                <label for='question'><b><font size='+1'> QUESTION: </font></b></label>\
                <input name='question[]' type='text' class='form-group' style='width:25%;'>\
                </div>\
                <div class='row'>\
                <label for='answer'>Answer 1:</label>\
                <input name='answer[]' type='text' class='form-check' placeholder='CORRECT ANSWER'>\
                </div><div class='row'>\
                <label for='answer'>Answer 2:</label>\
                <input name='answer[]' type='text' class='form-check'>\
                </div><div class='row'>\
                <label for='answer'>Answer 3:</label>\
                <input name='answer[]' type='text' class='form-check'>\
                </div><div class='row'>\
                <label for='answer'>Answer 4:</label>\
                <input name='answer[]' type='text' class='form-check'>\
                </div>";
                }
    </script>
</body>
</html>