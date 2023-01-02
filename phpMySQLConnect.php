<?php
    require_once 'dbconfig.php';

    try {
        $conn = new PDO ("mysql:host=$host;dbname=$dbname", $username, $password);
        echo "I'm in :sunglasses: (to $dbname at $host)";
    } catch (PDOException $pe) {
        die("Couldn't connect to database : $dbname :" . $pe->getMessage());
    }
>