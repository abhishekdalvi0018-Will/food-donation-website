<?php
session_start();
include 'connection.php';

if (isset($_POST['feedback'])) {
    try {
        // Validate email
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Prepare and execute statement
        $stmt = $connection->prepare("INSERT INTO user_feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", 
            $_POST['name'],
            $email,
            $_POST['message']
        );

        if ($stmt->execute()) {
            header("location: contact.html");
            exit();
        } else {
            throw new Exception('Failed to save feedback');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo '<script type="text/javascript">alert("' . htmlspecialchars($error) . '");</script>';
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>
