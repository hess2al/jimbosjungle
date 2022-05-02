<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $type = $date = $time = "";
$username_err = $type_err = $date_err = $time_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Validate type of trip
    if(empty(trim($_POST["type"]))){
        $type_err = "Please enter your trip type name.";
    } else{
        $type = trim($_POST["type"]);
    }
    // Validate trip date
    if(empty(trim($_POST["date"]))){
        $date_err = "Please enter your date of trip.";
    } else{
        $date = trim($_POST["date"]);
    }

    // Validate time of trip
    if(empty(trim($_POST["time"]))){
        $time_err = "Please enter your time of trip.";
    } else{
        $time = trim($_POST["time"]);
    }

    // Prepare an insert statement
    $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        // Set parameters
        $param_username = trim($_POST["username"]);
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Check if username exists, if yes then verify password
            if($stmt->rowCount() == 1){
                if($row = $stmt->fetch()){
                    $id = $row["id"];
                }
            }
        
        }
        unset($stmt);
    }

    $sql = "SELECT trip_id, type, date, time FROM trips WHERE username = :username";
        
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Check if username exists, if yes then verify password
            if($stmt->rowCount() == 1){
                if($row = $stmt->fetch()){
                    $id = $row["id"];
                    $trip_id = $row["trip_id"];
                    $type = $row["type"];
                    $date = $row["date"];
                    $time = $row["time"];
                }
            }
        
        }
        unset($stmt);
    }
        
    // Prepare a delete statement
    $sql = "DELETE FROM trips WHERE username = :username AND type = :type AND date = :date AND time = :time";
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        $stmt->bindParam(":type", $param_type, PDO::PARAM_STR);
        $stmt->bindParam(":date", $param_date, PDO::PARAM_STR);
        $stmt->bindParam(":time", $param_time, PDO::PARAM_STR);
        // Attempt to execute the prepared statement
        $param_username = trim($_POST["username"]);
        $param_type = trim($_POST["type"]);
        $param_date = trim($_POST["date"]);
        $param_time = trim($_POST["time"]);
        if($stmt->execute()){
            header("location: trips.php");
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            header("location: canceltrip.php");
        }
        // Close statement
        unset($stmt);
    }
    // Close connection
    unset($pdo);
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Trip</title>
    <link rel="stylesheet" href="../css/loginstyle.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Cancel A Trip</h2>
        <p>Please type the information for the trip you want to cancel.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Type:</label>
                <input type="text" name="type" class="form-control" value="<?php echo $type; ?>">
                <span class="invalid-feedback"><?php echo $type_err; ?></span>
            </div>
            <div class="form-group">
                <label>Date:</label>
                <input type="text" name="date" class="form-control" value="<?php echo $date; ?>">
                <span class="invalid-feedback"><?php echo $date_err; ?></span>
            </div>
            <div class="form-group">
                <label>Time:</label>
                <input type="text" name="time" class="form-control" value="<?php echo $time; ?>">
                <span class="invalid-feedback"><?php echo $time_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Want to book a new trip? <a href="trips.php">Click here.</a></p>
            <p>Want to return to the home page? <a href="../index.html">Click here.</a></p>
        </form>
    </div>    
</body>
</html>