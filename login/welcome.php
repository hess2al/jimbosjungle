<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../css/welcomestyle.css">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Jimbo's Australian Jungle Tours!</h1>
    <p>
        <a href="forgotpassword.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
        <a href="trips.php" class="btn btn-danger ml-3">Book A Trip</a>
        <a href="canceltrip.php" class="btn btn-danger ml-3">Cancel A Booked Trip</a>
    </p>
    <h1>Upcoming Trips:</h1>
</body>
</html>

<?php
require_once "config.php";

$sql = "SELECT type, date, time FROM trips WHERE username = :username";
$username = $_SESSION["username"];
if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
    // Set parameters
    $param_username = htmlspecialchars($_SESSION["username"]);

    // Attempt to execute the prepared statement
    if($stmt->execute()){
        // Check if username exists, if yes then verify password
        while ($row = $stmt->fetch()){
            echo "Type: " . $row["type"]. " - Date: " . $row["date"]. " - Time:" . $row["time"]. "<br>";
        }

    unset($stmt);
    }
}

?>