<?php
    // connect to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // check if successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>