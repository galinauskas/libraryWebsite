<?php
    session_start();
    require_once 'connect.php';

    /*
        search handler for "search.php". Queries the books table based
        on search critera. Placed at beginning so that upon button click
        for reserve() function page output would refresh
    */
    $sql = "SELECT books.isbn, books.booktitle, books.author, books.edition, books.year, books.category, books. reserved, categories.categoryDescription FROM books 
            INNER JOIN categories ON books.category = categories.categoryID
            WHERE books.category LIKE '%".$_SESSION['category']."%' AND (books.booktitle LIKE '%".$_SESSION['search']."%' OR books.author LIKE '%".$_SESSION['search']."%')";
        
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

    // save results
    $_SESSION['results'] = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">

    <title>Search</title>
</head>
<body>
    <?php include 'header.php';?>

    <!-- search -->
    <div class="window">

        <h2>Search</h2>

        <form action="search.php" method="POST">
            <input type="text" id="search" name="search" placeholder="book title/author">

            <select id="categories" name="categories">
                <option value="">any</option>
                <option value="1">Health</option>
                <option value="2">Business</option>
                <option value="3">Biography</option>
                <option value="4">Technology</option>
                <option value="5">Travel</option>
                <option value="6">Self-Help</option>
                <option value="7">Cookery</option>
                <option value="8">Fiction</option>
            </select>

            <input type="submit" value="Search">
        </form>

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

    <?php

        // check is user is logged in
        if(isset($_SESSION['user'])) {

            // on search
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // update search criteria
                $_SESSION['search'] = $_POST["search"];
                $_SESSION['category'] = $_POST["categories"];
                
                // refresh page
                header("Location: search.php");
            }

            // display query results
            if (isset($_SESSION['results'])) {

                echo '<div class="resultsContainer">';

                    // display results
                    foreach ($_SESSION['results'] as $row) {

                        echo '<div class="result">';

                            // display headings
                            echo "<span class='isbn'>" . $row["isbn"] . "</span><br>";
                            echo "<span class='title'>" . $row["booktitle"] . "</span><br>";
                            echo "<span class='author'>" . $row["author"] . "</span><br>";
                            echo "<span class='year'>" . $row["year"] . "</span><br>";
                            echo "<span class='edition'>Edition: " . $row["edition"] . "</span><br>";
                            echo "<span class='category'>" . $row["categoryDescription"] . "</span><br><br>";

                            // display reserve button
                            if ($row["reserved"] == 'N') {
                                echo "<form action='functions.php' method='POST'>";
                                echo "<input type='hidden' name='isbn' value='" . $row["isbn"] . "'>";
                                echo "<button type='submit' name='reserve'>Reserve</button>";
                                echo "</form>";
                            } else {
                                // unavailable button
                                echo "<button type='button' class='unavailable' disabled>Unavailable</button>";
                            }   

                        echo '</div>';

                    } // end foreach

                echo '</div>';

                // page indicator
                echo '<div class="pagination">';
                    for ($page = 1; $page <= $number_of_page; $page++) {  
                        echo '<a href="search.php?page=' . $page . '" class="number">' . $page . ' </a>';
                    }
                echo '</div>';

            } // end if

        } else {
            // must be logged in
            header("Location: index.php");
        }

        include 'footer.php';
    ?>

</body>
</html>