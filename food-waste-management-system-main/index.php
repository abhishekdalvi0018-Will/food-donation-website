<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to home page
    header("Location: home.html");
    exit();
} else {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
?>