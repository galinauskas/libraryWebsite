<?php
    session_start();
    require_once 'connect.php';

    if(isset($_POST['reserve'])) {
        $isbn = $_POST['isbn'];
        reserve($isbn);
    }

    if(isset($_POST['remove'])) {
        $isbn = $_POST['isbn'];
        remove($isbn);
    }

    function reserve($isbn) {
        global $conn;

        // queries
        $edit = "UPDATE books SET reserved = 'Y' WHERE isbn = '$isbn'";
        $insert = "INSERT INTO reserved (isbn, username, reserveddate) VALUES ('$isbn', '{$_SESSION['user']}', CURDATE())";

        // execute
        $conn->query($edit);
        $conn->query($insert);

        header("Location: search.php");
    }

    function remove($isbn) {
        global $conn;

        // queries
        $edit = "UPDATE books SET reserved = 'N' WHERE isbn = '$isbn'";
        $delete = "DELETE FROM reserved WHERE isbn = '$isbn'";

        // execute
        $conn->query($edit);
        $conn->query($delete);

        header("Location: reserved.php");
    }
?> 