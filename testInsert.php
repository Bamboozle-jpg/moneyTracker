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

            public function printRows() {
                $sql = 'SELECT id, title, description, location, date, amount
                    FROM money
                    ORDER BY id DESC';
                $q = $this->pdo->query($sql);
                $q->setFetchMode(PDO::FETCH_ASSOC);

                while ($r = $q->fetch()) { ?>
                    <div> <?php echo sprintf('%s <br/>', $r['id']); ?></div>
                    <div> <?php echo sprintf('%s <br/>', $r['title']); ?></div>
                    <div> <?php echo sprintf('%s <br/>', $r['description']); ?></div>
                    <div> <?php echo sprintf('%s <br/>', $r['location']); ?></div>
                    <?php 
                        $year = substr($r['date'], 0, 4);
                        $month = substr($r['date'], 5, -3);
                        $day = substr($r['date'], 8, 9);
                        $dateTemp = $month;
                        $dateTemp .= "/";
                        $dateTemp = $day;
                        $dateTemp .= "/";
                        $dateTemp = $year;
                        $date = date("F j, Y", strtotime($dateTemp)); ?>

                    <div> <?php echo sprintf('%s <br/>', $date); ?></div>
                    <div> <?php echo "$ ".number_format($r['amount'], 2); ?></div>
                <?php }
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
                    return $q->execute($task);
                }
                catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        $title = "Test";
        $description = "Test Description";
        $location = 1;
        $amount = 12345.67;

        $object = new insertData($host, $dbname, $username, $password);
        ?><div>Test</div><?php
        $object->printRows();
        $object->insert($title, $description, $location, $amount);
    ?>
    <div>Added Successfully</div>
</body>

</html>