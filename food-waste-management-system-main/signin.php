<?php
session_start();
include 'connection.php';

if (isset($_POST['sign'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 2; // Invalid email format
    } else {
        $email = mysqli_real_escape_string($connection, $email);
        $password = $_POST['password'];

        $stmt = $connection->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = htmlspecialchars($row['name']);
                $_SESSION['gender'] = htmlspecialchars($row['gender']);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['last_activity'] = time();

                header("location: home.html");
                exit();
            } else {
                $msg = 1; // Password not match
            }
        } else {
            $msg = 3; // Account does not exist
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
    <title>Sign In - Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <style>.uil { top: 42%; }</style>
    <div class="container">
        <div class="regform">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <p class="logo">Food <b style="color:#06C167;">Donate</b></p>
                <p id="heading" style="padding-left: 1px;">Welcome back!</p>
                <?php
                if (isset($msg)) {
                    if ($msg == 1) {
                        echo '<p class="error">Invalid password. Please try again.</p>';
                    } else if ($msg == 2) {
                        echo '<p class="error">Invalid email format.</p>';
                    } else if ($msg == 3) {
                        echo '<p class="error">Account does not exist.</p>';
                    }
                }
                ?>
                <div class="input">
                    <input type="email" placeholder="Email address" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
                </div>
                <div class="password">
                    <input type="password" placeholder="Password" name="password" id="password" required />
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>
                <div class="btn">
                    <button type="submit" name="sign">Sign in</button>
                </div>
                <div class="signin-up">
                    <p id="signin-up">Don't have an account? <a href="signup.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="login.js"></script>
</body>
</html>
