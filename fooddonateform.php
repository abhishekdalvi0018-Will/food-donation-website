<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("location: signin.php");
    exit();
}

$emailid = $_SESSION['email'];

if(isset($_POST['submit'])) {
    try {
        // Prepare the SQL statement
        $stmt = $connection->prepare("INSERT INTO food_donations (email, food, type, category, phoneno, location, address, name, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Bind parameters
        $stmt->bind_param("sssssssss", 
            $emailid,
            $_POST['foodname'],
            $_POST['meal'],
            $_POST['image-choice'],
            $_POST['phoneno'],
            $_POST['district'],
            $_POST['address'],
            $_POST['name'],
            $_POST['quantity']
        );
        
        // Execute the statement
        if($stmt->execute()) {
            header("location: delivery.html");
            exit();
        } else {
            throw new Exception("Failed to save donation data");
        }
    } catch (Exception $e) {
        $error = "An error occurred while processing your donation. Please try again.";
    } finally {
        if(isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
                <?php if(isset($error)) { echo '<p class="error">' . htmlspecialchars($error) . '</p>'; } ?>
                
                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>
                
                <div class="radio">
                    <label for="meal">Meal type:</label><br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg">
                    <label for="Non-veg">Non-veg</label>
                </div>
                
                <div class="input">
                    <label for="food">Select the Category:</label><br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food" required>
                        <label for="raw-food">
                            <img src="img/raw-food.png" alt="raw-food">
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food">
                        <label for="cooked-food">
                            <img src="img/cooked-food.png" alt="cooked-food">
                        </label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food">
                            <img src="img/packed-food.png" alt="packed-food">
                        </label>
                    </div>
                </div>
                
                <div class="input">
                    <label for="quantity">Quantity (number of person/kg):</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>
                
                <b><p style="text-align: center;">Contact Details</p></b>
                
                <div class="input">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required/>
                </div>
                
                <div class="input">
                    <label for="phoneno">Phone Number:</label>
                    <input type="tel" id="phoneno" name="phoneno" pattern="[0-9]{10}" required/>
                </div>
                
                <div class="input">
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" required/>
                </div>
                
                <div class="input">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                
                <div class="btn">
                    <button type="submit" name="submit">Submit Donation</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>