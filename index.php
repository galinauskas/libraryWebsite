<?php
    session_start();
    require_once 'connect.php';

    session_unset(); // clear user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">

    <title>Home</title>
</head>
<body>
    <?php include 'header.php';?>

    <!-- login form -->
    <div class="window">

        <h2>Login</h2>

        <form action="" method="POST">
            <input type="text" id="username" name="username" placeholder="username">
            <input type="password" id="password" name="password" placeholder="password">
            <input type="submit" value="Login">
        </form>
        
        <!-- link to register -->
        <p>Not a member? <a href="register.php">Signup</a></p>

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
    /*  
        login handler
    */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // take form input
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT username, password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        // check username (if there is a row then user is registered)
        if ($result->num_rows > 0) {
            
            $row = $result->fetch_assoc();

            // check password (compare db to user input)
            if ($password == $row["password"]) {
                // set session user
                $_SESSION['user'] = $row["username"];

                // correct password, send to "search.php"
                header("Location: search.php");
            } else {
                // incorrect password
                $_SESSION['error'] = "Incorrect password, try again.";
                header("Location: index.php");

            } // end if
            
        } else {
            // user does not exist
            $_SESSION['error'] = "User does not exist.";
            header("Location: index.php");

        } // end if

    } // end if

    include 'footer.php';
?>