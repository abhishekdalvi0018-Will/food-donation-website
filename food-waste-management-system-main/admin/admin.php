
<?php
ob_start(); 
include("connect.php"); 
if($_SESSION['name']==''){
	header("location:signin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard Panel</title> 
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image"></div>
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dashboard</span>
                </a></li>
                <li><a href="analytics.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Analytics</span>
                </a></li>
                <li><a href="donate.php">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donates</span>
                </a></li>
                <li><a href="feedback.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Feedbacks</span>
                </a></li>
                <li><a href="adminprofile.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a></li>
            </ul>
            
            <ul class="logout-mode">
                <li><a href="../logout.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                </a>
                <div class="mode-toggle">
                  <span class="switch"></span>
                </div>
            </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
            <p class="user"></p>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                        try {
                           $query = "SELECT count(*) as count FROM login";
                           $result = mysqli_query($connection, $query);
                           if (!$result) {
                               throw new Exception(mysqli_error($connection));
                           }
                           $row = mysqli_fetch_assoc($result);
                           echo "<span class=\"number\">".$row['count']."</span>";
                        } catch (Exception $e) {
                           echo "<span class=\"number\">Error loading count</span>";
                        }
                        ?>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                        try {
                           $query = "SELECT count(*) as count FROM user_feedback";
                           $result = mysqli_query($connection, $query);
                           if (!$result) {
                               throw new Exception(mysqli_error($connection));
                           }
                           $row = mysqli_fetch_assoc($result);
                           echo "<span class=\"number\">".$row['count']."</span>";
                        } catch (Exception $e) {
                           echo "<span class=\"number\">Error loading count</span>";
                        }
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total donations</span>
                        <?php
                        try {
                           $query = "SELECT count(*) as count FROM food_donations";
                           $result = mysqli_query($connection, $query);
                           if (!$result) {
                               throw new Exception(mysqli_error($connection));
                           }
                           $row = mysqli_fetch_assoc($result);
                           echo "<span class=\"number\">".$row['count']."</span>";
                        } catch (Exception $e) {
                           echo "<span class=\"number\">Error loading count</span>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>
                <div class="get">
                <?php
                try {
                    $loc = $_SESSION['location'];
                    $id = $_SESSION['Aid'];

                    // Define the SQL query to fetch unassigned orders
                    $sql = "SELECT * FROM food_donations WHERE assigned_to IS NULL AND location = ?";
                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $loc);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (!$result) {
                        throw new Exception(mysqli_error($connection));
                    }

                    // Fetch the data as an associative array
                    $data = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }

                    // If the delivery person has taken an order, update the assigned_to field
                    if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
                        $order_id = $_POST['order_id'];
                        $delivery_person_id = $_POST['delivery_person_id'];
                        
                        // Check if order is already assigned
                        $check_sql = "SELECT * FROM food_donations WHERE Fid = ? AND assigned_to IS NOT NULL";
                        $check_stmt = mysqli_prepare($connection, $check_sql);
                        mysqli_stmt_bind_param($check_stmt, "i", $order_id);
                        mysqli_stmt_execute($check_stmt);
                        $check_result = mysqli_stmt_get_result($check_stmt);

                        if (mysqli_num_rows($check_result) > 0) {
                            throw new Exception("Sorry, this order has already been assigned to someone else.");
                        }
                    }
                } catch (Exception $e) {
                    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
                </div>
            </div>
        </div>
    </section>
    <script src="admin.js"></script>
</body>
</html>
