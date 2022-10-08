<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to main page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($staff_check == "TRUE")
        header("location: main_staff.php");
        exit;
    header("location: main_student.php");
    exit;
}

// Include config file
require_once "config.php";


$username = $password = "";
$username_err = $password_err = "";
$staff_check = "FALSE";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    }else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    }else{
        $password = trim($_POST["password"]);
    }
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $recieved_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $recieved_password){
                            session_start();
                
                            // Store session data
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["quiz_id"] = "";

                            if(isset($_POST["checkBox"]) && strtolower($_POST["checkBox"]) == "staff")
                                $staff_check = "TRUE";

                            if($staff_check == "TRUE"){
                                header("location: main_staff.php");
                            }else{
                            header("location: main_student.php");
                            }
                        }else{
                            $password_err = "The password is not valid.";
                        }
                    }
                }else{
                    $username_err = "No account with that username.";
                }
            }else{
                echo "Something went wrong";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Log In</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="hidden" name="checkBox" value="student">
                <p style="font-size:+150%;" >Check box to log in as <b>STAFF:</b>   <input type="checkbox" name="checkBox" value="staff"> </p>
                <p style="font-size:+140%;" >Otherwise log in as <b>STUDENT</b></p>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Log In">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>