<head>
<title>Test Page</title>
</head>
<?php
    include 'dbconfig.php';

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // echo "working...";
    // $sql = 'SELECT id, title, description, location, date, amount FROM money ORDER BY id DESC';
    // $q = $conn->query($sql);
    // $q->setFetchMode(PDO::FETCH_ASSOC);

    $ID = $_POST['id'];
    echo $ID;
    $strFinal = (string) $ID;
    $sql = "DELETE FROM money WHERE id=" . $strFinal;
    // echo $sql
    $return = $conn->exec($sql);


?>
