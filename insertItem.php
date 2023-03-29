<!DOCTYPE html>
<html>
<head>
<title>Test Page</title>
</head>

<body>
    <div>Test works</div>
    <?php
        include 'dbconfig.php';

        class insertData {
            var $host;
            var $dbname;
            var $username;
            var $passowrd;
            private $pdo = null;

            public function __construct($host, $dbname, $username, $password) {
                $this->host = $host;
                $this->dbname = $dbname;
                $this->username = $username;
                $this->password = $password;

                // open database connection
                $conStr = sprintf("mysql:host=%s;dbname=%s", $this->host, $this->dbname);
                try {
                    $this->pdo = new PDO($conStr, $this->username, $this->password);
                    ?><div>Connection secured</div><?php
                } catch (PDOException $pe) {
                    die($pe->getMessage());
                }
            }

            public function insert($title, $description, $location, $amount) {

                $task = array(':title' => $title,
                    ':description' => $description,
                    ':location' => $location,
                    ':amount' => $amount
                );

                $sql = "INSERT INTO money (
                            title,
                            description,
                            location,
                            date,
                            amount
                        )
                        Values (
                            :title,
                            :description,
                            :location,
                            now(),
                            :amount
                        )";
                ?><div><?php echo $location ?></div><?php

                $q = $this->pdo->prepare($sql);
                ?><div>test 3</div><?php
                try {
                    echo $q->execute($task);
                }
                catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        $amount = $_POST['amount'];
        $title = $_POST['title'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        $object = new insertData($host, $dbname, $username, $password);
        $object->insert($title, $description, $location, $amount);
    ?>
    <div>Added Successfully</div>
</body>

</html>