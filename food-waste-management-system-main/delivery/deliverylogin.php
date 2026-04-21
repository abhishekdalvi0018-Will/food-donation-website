<?php
session_start();
include '../connection.php'; 
$msg = 0;

if (isset($_POST['sign'])) {
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sanitized_emailid = mysqli_real_escape_string($connection, $email);
        $sanitized_password = mysqli_real_escape_string($connection, $password);

        $sql = "SELECT * FROM delivery_persons WHERE email = ?";
        $stmt = mysqli_prepare($connection, $sql);
        if (!$stmt) {
            throw new Exception("Database error: " . mysqli_error($connection));
        }

        mysqli_stmt_bind_param($stmt, "s", $sanitized_emailid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            throw new Exception("Query failed: " . mysqli_error($connection));
        }

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($sanitized_password, $row['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['Did'] = $row['Did'];
                $_SESSION['city'] = $row['city'];
                header("location:delivery.php");
                exit();
            } else {
                $msg = 1;
                echo "<div class='error-message'>Invalid password. Please try again.</div>";
            }
        } else {
            echo "<div class='error-message'>Account does not exist. Please check your email or sign up.</div>";
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "<div class='error-message'>An error occurred. Please try again later.</div>";
    }
}
?>



<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Login Form</title>
    <link rel="stylesheet" href="deliverycss.css">
  </head>
  <body>
    <div class="center">
      <h1>Delivery Login</h1>
      <form method="post">
        <div class="txt_field">
          <input type="email" name="email" required/>
          <span></span>
          <label>Email</label>
        </div>
        <div class="txt_field">
          <input type="password" name="password" required/>
          <span></span>
          <label>Password</label>
          
        </div>
        <?php
        if($msg==1){
                        // echo ' <i class="bx bx-error-circle error-icon"></i>';
                        echo '<p class="error">Password not match.</p>';
                    }
                    ?>
                    <br>
        <!-- <div class="pass">Forgot Password?</div> -->
        <input type="submit" value="Login" name="sign">
        <div class="signup_link">
          Not a member? <a href="deliverysignup.php">Signup</a>
        </div>
      </form>
    </div>

  </body>
</html>
