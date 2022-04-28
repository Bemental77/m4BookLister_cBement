<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Lister - Using PDO (PHP Data Object) to work with Databases in PHP</title>

    <link href="css/bookLister.css" rel="stylesheet">
</head>
<body>

<div class="wrapper">

    <?php

    //Display any DB-relate errors to the webpage rather than placing them in a log file.
    //Remove these from production code.
    ini_set('display_errors', 1);
    error_reporting(-1); //level of -1 says display all errors

    require 'sanitize.php';

    //Run passed-in query returning result set (PDOStatement object)
    //on success or generate error message (or exit).

    function callQuery($pdo, $query, $error){
        try{
           return $categoryResult = $pdo->query($query);
        } catch (PDOException $ex) {

            //note that we should get rid of all system-generated error data befoer putting this code into production
            $error .= $ex->getMessage();
            include 'error.html.php';
            throw $ex;
            //exit();

        }

    }

    //Include the code to connect to our db and login to it
    require 'dbConnect.php';

    //
    //Check if user wishes to add a new book
    //
    $addNewBookLinkClicked = sanitizeString(INPUT_GET, "clicked");

    //Check to see if the user clicked the add a new book title link
    if ($addNewBookLinkClicked == 1){

        //display the add new book form
        ?>
        <form action="" method="post">
            <label for="newBookTitle" id="bookArea">Enter the book's title</label><br>
            <textarea name="newBookTitle" id="newBookTitle" cols="40" rows="10">Enter book title</textarea>
            <br><br>

            <label for="newAuthor" id="bookAuthor">Enter the author of this book</label><br>
            <input type="text" name="newAuthor" id="newAuthor">
            <br><br>

            <label for="bookCategory" id="genre">Enter book category</label><br>
            <select name="bookCategory" id="bookCategory">

                <?php
                $closeSelect = true;

                $categoryResult = callQuery($pdo, 'SELECT * FROM categories ORDER BY name', 'Error fetching book categories');

                //now, step through the result set one row at a time
                while ($row = $categoryResult->fetch()){
                    ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option><?php



                }

                ?>

            </select>
            <br><br>

            <input type="submit" name="addBook" value="Add Book">
        </form>


        <?php

    } //end if add new book link was clicked


    echo"\t<h2 id=\"topHeading\">The Book Review</h2>\n";


    //Check if user submitted the add a new book form and if so, validate their entered data
    $newBookTitle = sanitizeString(INPUT_POST, 'newBookTitle');
    $newBookFormSubmit = sanitizeString(INPUT_POST, 'addBook');

    if (!empty(trim($newBookTitle))  && $newBookTitle != "Enter book title"){

        $newBookAuthor = sanitizeString(INPUT_POST, 'newAuthor');

        //now, we have a valid book title, so let's check if an author has been entered for the book
        if (empty(trim($newBookAuthor))){
            $newBookAuthor = 'Anonymous';
        }

        // Replace any single quotes in our book title with an escaped single quote so query does not fail
        $newBookTitle = str_replace("'", "\\'", $newBookTitle);
        $newBookAuthor = str_replace("'", "\\'", $newBookAuthor);

        //Check if new book title already exists in our DB
        $query = "SELECT COUNT(bookTitle) FROM bookstuff WHERE bookTitle = '$newBookTitle'";

        $errorMsg = "Error fetching book title";

        $numBookTitles = callQuery($pdo, $query, $errorMsg)->fetchColumn();

        //Did we find the new book title in our bookstuff table (duplicate)?
        if (!$numBookTitles){ //new book was not found, so add it

            echo "\t<h3 style=\"color: #fff;\">New book title $newBookTitle added</h3>\n";

            //We know we want to add a new book, also check if the new books author already exists
            $query = "SELECT COUNT(*) FROM authors WHERE authorName = '$newBookAuthor'";

            $errorMsg = "Error fetching book author";

            $numAuthorRows = callQuery($pdo, $query,$errorMsg)->fetchColumn();

            //Did we find the author?
            if (!$numAuthorRows){ // new author was not found in authors table
                try{

                    //Use an SQL prepared statement to prevent SQL injection attacks with this insert of our new author
                    //
                    //PDO is smart enough to guard against dangerous characters automatically
                    //
                    //Use a transaction as a fail-safe to roll back the database to its previous state if something goes wrong with the query
                    //
                    $pdo->beginTransaction();

                    //$sql = "INSERT INTO authors SET authorName=:newAuthor";
                    $sql = "INSERT INTO authors (authorName) VALUES (?)";

                    //Create prepared statement
                    $s = $pdo->prepare($sql); //$s is a PDOStatement object because prepare() returns one

                    //Execute the query and then commit
                    $s->execute([$newBookAuthor]);

                    $s->commit();

                } catch (PDOException $ex) {

                    $pdo->rollBack();
                    $error = 'Error performing insert of author natme: '. $ex->getMessage();
                    include 'error.html.php';
                    throw $ex;
                    //exit();

                }


            } else { //New author is a duplicate
                echo "\t<h3 style=\"color: #fff;\">New book author $newBookAuthor already exists - not added</h3>\n";

            }

            //Now we need to obtain the new books authorId
            $query = "SELECT id FROM authors WHERE authorName = '$newBookAuthor'";

            $errorMessage = 'Error fetching book author id: ';

            $newAuthorResult = callQuery($pdo, $query, $errorMessage);

            //Extract the author's id from the result set directly
            $row = $newAuthorResult->fetch();

            $newAuthorId = $row['id'];

            //Get the new book's category id (genre)
            $newBookGenreId = sanitizeString(INPUT_POST, 'bookCategory');

            if (!isset($newBookGenreId)){
                $newBookGenreId = -1;
            }

            //Ready to insert the new book
            try{


                $pdo->beginTransaction();


                $sql = "INSERT INTO bookstuff (bookTitle, catId, authorId) VALUES (?,?,?)";

                $s = $pdo->prepare($sql); //$s is a PDOStatement object because prepare() returns one

                $s->execute([$newBookTitle, $newBookGenreId, $newAuthorId]);

                $s->commit();

            } catch (PDOException $ex) {

                $pdo->rollBack();
                $error = 'Error performing insert of new book: '. $ex->getMessage();
                include 'error.html.php';
                throw $ex;
                //exit();

            }


        } else { //new book is a duplicate
            echo "\t<h3 style=\"color: #fff;\">New book title $newBookTitle already exists - not added</h3>\n";
        }


    } elseif (isset($newBookFormSubmit)){
        echo "\t<h3>No valid new book was entered</h3>\n";

    }





    //run a query to retrieve our book categories

   /* try{
        $categoryResult = $pdo->query('SELECT * FROM categories');
    } catch (PDOException $ex) {

    } */

    $query = 'SELECT * FROM categories';
    $errorMsg = 'Error fetching book categories';

    $categoryResult = callQuery($pdo, $query, $errorMsg);

    //Step through the categories in our result set (PDOStatement object)
    //While there are remaining rows in the result set, fetch the next row
    //
    while ($bookType = $categoryResult->fetch()){

        $genreId = $bookType['id'];
        $genreName = $bookType['name'];

        ?>
        <div class="bookGenre">
            <h3><?= $genreName ?></h3>

            <?php
            //Run another query to obtain all the book titles and their authors for the current category (using $genreId)
            //Order these by book title
            $query = "SELECT bookTitle, authorName FROM bookstuff, authors
                      WHERE bookstuff.authorId = authors.id AND bookstuff.catId = $genreId
                      ORDER BY 1";

            $errorMsg = 'Error fetching book info: ';

            $booksResult = callQuery($pdo, $query, $errorMsg);
            ?>
            <blockquote>





            <?php
            //Step through the $booksResult result set (POStatement object) and display each book in the category
            while ($book = $booksResult->fetch()){

                ?>
                <p>
                    <?= $book['bookTitle'] ?><br>
                    <span class="author"><?= $book['authorName'] ?></span>
                </p>
                <?php

            } //end inner while remaining book row


            ?>
            </blockquote>
        </div>
    <?php

    } //end outer while fetch next category row

    ?>

    <br><br><?php
    $thisPage = sanitizeString(INPUT_SERVER, "PHP_SELF");
    ?>

    <a href="<?php echo "$thisPage?clicked=1" ?>">Add new book title!</a>
    <?php


    ?>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/slidePanes.js"></script>

</body>
</html>