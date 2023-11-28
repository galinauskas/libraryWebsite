<?php
    session_start();
    require_once 'connect.php';
?>

<header>

    <h2><a href="index.php">Library</a></h2>

    <ul>
        <?php
            if(!isset($_SESSION['user'])) {
                echo "<li>";
                echo "<a href='index.php'>Login</a>";
                echo "</li>";
                echo "<li>";
                echo "<a href='register.php'>Register</a>";
                echo "</li>";
            }
        ?>

        <?php
            if(isset($_SESSION['user'])) {
                echo "<li>";
                echo "<a href='search.php'>Search</a>";
                echo "</li>";
                echo "<li>";
                echo "<a href='reserved.php'>My Reservations</a>";
                echo "</li>";
            }
        ?>
    </ul>
</header>