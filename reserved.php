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

    <title>Reserved</title>
</head>
<body>
    <?php include 'header.php';?>

    <h2 class="heading">My Reservations</h2>

    <?php
        // display reserved books by user
        if(isset($_SESSION['user'])) {
            $user = $_SESSION['user'];

            $sql = "SELECT books.isbn, books.booktitle, books.author, books.edition, books.year, books.category, books.reserved, categories.categoryDescription FROM books 
                    INNER JOIN categories ON books.category = categories.categoryID
                    INNER JOIN reserved ON books.isbn = reserved.isbn
                    WHERE reserved.username = '$user'";

            $result = $conn->query($sql);

            // identifying page
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }

            // pagination criteria
            $results_per_page = 5;
            $offset = ($page - 1) * $results_per_page; 
            $number_of_result = mysqli_num_rows($result);
            $number_of_page = ceil($number_of_result / $results_per_page); 

            // modifed sql for pagination
            $sql .= " LIMIT " . $offset . "," . $results_per_page;
            $result = $conn->query($sql);

            // if there are books reserved by user
            if ($result->num_rows > 0) {

                echo '<div class="resultsContainer">';

                    foreach ($result as $row) {

                        echo '<div class="result">';

                            // display headings
                            echo "<span class='isbn'>" . $row["isbn"] . "</span><br>";
                            echo "<span class='title'>" . $row["booktitle"] . "</span><br>";
                            echo "<span class='author'>" . $row["author"] . "</span><br>";
                            echo "<span class='year'>" . $row["year"] . "</span><br>";
                            echo "<span class='edition'>Edition: " . $row["edition"] . "</span><br>";
                            echo "<span class='category'>" . $row["categoryDescription"] . "</span><br>";

                            // button to remove reservation
                            echo "<form action='functions.php' method='POST'>";
                            echo "<input type='hidden' name='isbn' value='" . $row["isbn"] . "'>";
                            echo "<button type='submit' name='remove'>Remove</button><br><br>";
                            echo "</form>";

                        echo '</div>';
                    }

                echo "</div>";

                // page indicator
                echo '<div class="pagination">';
                    for ($page = 1; $page <= $number_of_page; $page++) {
                        echo '<a href="reserved.php?page=' . $page . '" class="number">' . $page . '</a>';
                    }
                echo '</div>';

            } else {
                // no books reserved. Send back to search
                $_SESSION['error'] = "User has no books reserved.";
                header("Location: search.php");
            }

        } else {
            // must be logged in
            header("Location: index.php");
        }

        include 'footer.php';
    ?>

</body>
</html>