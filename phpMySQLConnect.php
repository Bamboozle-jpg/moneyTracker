<?php
    require_once 'dbconfig.php';

    try {
        $conn = new PDO ("mysql:host=$host;dbname=$dbname", $username, $password)
    }