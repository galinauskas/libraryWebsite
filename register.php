<?php
    session_start();
    require_once 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">

    <title>Register</title>
</head>
<body>
    <?php include 'header.php';?>

    <!-- register form -->
    <div class="window">

        <h2>Register</h2>

        <form action="" method="POST">
            <input type="text" id="username" name="username" placeholder="Username">
            <input type="password" id="password" name="password" placeholder="Password">
            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password">
            <input type="text" id="firstName" name="firstName" placeholder="First Name">
            <input type="text" id="surname" name="surname" placeholder="Surname">
            <input type="text" id="addressLine1" name="addressLine1" placeholder="Address Line 1">
            <input type="text" id="addressLine2" name="addressLine2" placeholder="Address Line 2">
            <input type="text" id="city" name="city" placeholder="City">
            <input type="text" id="telephone" name="telephone" placeholder="Telephone">
            <input type="text" id="mobile" name="mobile" placeholder="Mobile">
            <input type="submit" value="Register">
        </form>

        <!-- link to login -->
        <p>Already a member? <a href="index.php">Login</a></p>

        <?php 
            // display error
            if(isset($_SESSION['error'])) {
                echo '<div class="error">';
                echo "<p>" . $_SESSION['error'] . "</p>";
                echo '</div>';
                unset($_SESSION['error']);
            }
        ?>
    </div>

</body>
</html>

<?php
    // register handler
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // take form input
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirmpassword = $_POST["confirmpassword"];
        $firstname = $_POST["firstName"];
        $surname = $_POST["surname"];
        $addressLine1 = $_POST["addressLine1"];
        $addressLine2 = $_POST["addressLine2"];
        $city = $_POST["city"];
        $telephone = $_POST["telephone"];
        $mobile = $_POST["mobile"];

        // insert details into an array for more optimised validation
        $details = array($username, $password, $firstname, $surname, $addressLine1, $addressLine2, $city, $telephone, $mobile);

        // check details array that each detail is set and not empty
        foreach ($details as $detail) {
            if (!isset($detail) || empty($detail)) {
                $_SESSION['error'] = "Please fill in all your details.";
                header("Location: register.php");
                exit(); // exit loop if empty or unset detail
            }
        }

        // check that password is at least 6 characters long
        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password is too short.";
            header("Location: register.php");
        }

        // check if passwords match
        if ($confirmpassword != $password) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: register.php");
        }

        // Check if the mobile is numeric and 10 characters in length
        if (!is_numeric($mobile) || strlen($mobile) != 10) {
            $_SESSION['error'] = "Mobile number should be numeric and 10 characters in length.";
            header("Location: register.php");
        }

        // check if username is unique
        $sql = "SELECT username FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Username already exists.";
            header("Location: register.php");
        }

        // if registration form correctly filled
        if (empty($_SESSION['error'])) {
            // insert user data into users table
            $sql = "INSERT INTO users (username, password, firstname, surname, addressLine1, addressLine2, city, telephone, mobile) VALUES ('$username', '$password', '$firstname', '$surname', '$addressLine1', '$addressLine2', '$city', '$telephone', '$mobile')";
            $result = $conn->query($sql);

            // check if successful
            if ($result === TRUE) {
                // set session username
                $_SESSION['username'] = $row["username"];

                // send to "search.php"
                header("Location: search.php");
            } else {
                echo "Error inserting user data: " . $conn->error;
                
            } // end if

        } // end if

    } // end if
    
    include 'footer.php';
?>