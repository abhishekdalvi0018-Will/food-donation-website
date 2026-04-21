<?php
session_start();
include 'connection.php';

if(isset($_POST['sign'])) {
    $username = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // Check if email already exists using prepared statement
        $stmt = $connection->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $error = "Account already exists";
        } else {
            // Hash password
            $pass = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user using prepared statement
            $stmt = $connection->prepare("INSERT INTO login (name, email, password, gender) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $pass, $gender);
            
            if($stmt->execute()) {
                header("location: signin.php");
                exit();
            } else {
                $error = "Error creating account. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <div class="container">
        <div class="regform">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
                <p id="heading">Create your account</p>
                <?php if(isset($error)) { echo '<p class="error">' . htmlspecialchars($error) . '</p>'; } ?>
                
                <div class="input">
                    <label class="textlabel" for="name">User name</label>
                    <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"/>
                </div>
                
                <div class="input">
                    <label class="textlabel" for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"/>
                </div>
                
                <div class="password">
                    <label class="textlabel" for="password">Password</label>
                    <input type="password" name="password" id="password" required/>
                    <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>
                </div>
                
                <div class="radio">
                    <input type="radio" name="gender" id="male" value="male" required <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'checked' : ''; ?>/>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'checked' : ''; ?>/>
                    <label for="female">Female</label>
                </div>
                
                <div class="btn">
                    <button type="submit" name="sign">Create Account</button>
                </div>
                
                <div class="signin-up">
                    <p>Already have an account? <a href="signin.php">Sign in</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="login.js"></script>
</body>
</html>