<?php
session_start();
include '../connection.php';

$msg = 0;

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['sign'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $sanitized_email = mysqli_real_escape_string($connection, $email);
        $sanitized_password = mysqli_real_escape_string($connection, $password);

        $sql = "SELECT * FROM admin WHERE email = '$sanitized_email'";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($sanitized_password, $row['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['location'] = $row['location'];
                $_SESSION['Aid'] = $row['Aid'];

                header("Location: admin.php");
                exit;
            } else {
                $msg = 1;
            }
        } else {
            $msg = 2; // Account does not exist
        }
    } else {
        $msg = 3; // Empty fields
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if ($msg == 1): ?>
        <p style="color:red; text-align:center;">Incorrect password. Try again.</p>
    <?php elseif ($msg == 2): ?>
        <p style="color:red; text-align:center;">Account does not exist.</p>
    <?php elseif ($msg == 3): ?>
        <p style="color:red; text-align:center;">Please fill in all fields.</p>
    <?php endif; ?>
</body>
</html>
