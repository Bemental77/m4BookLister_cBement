<?php
//1. Connect to db server
//2. select our db
//3. provide login info
//4. check for exceptions

try{

    //Create an instance of the PDO class and connect
    $pdo = new PDO('mysql:host=localhost:3306;dbname=webbooks', 'itsd', 'mysql');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');

} catch (PDOException $ex){

    $error = "Unable to connect to the database server<br><br>" . $ex->getMessage();

    if ($closeSelect){
        echo "</select>";
        $closeSelect = false;
    }

    include 'error.html.php';
    throw $ex; //also show SQL system syntax errors
    //exit();

}