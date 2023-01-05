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

            public function test() {
                $sql = 'SELECT id, numOfSomething, date, location, title
                FROM testTable
                ORDER BY date';
            $q = $this->pdo->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);

            while ($r = $q->fetch()) { ?>
                <div> <?php echo sprintf('%s <br/>', $r['id']); ?></div>
                <div> <?php echo sprintf('%s <br/>', $r['numOfSomething']); ?></div>
                <div> <?php echo sprintf('%s <br/>', $r['date']); ?></div>
                <div> <?php echo sprintf('%s <br/>', $r['location']); ?></div>
                <div> <?php echo sprintf('%s <br/>', $r['title']); ?></div>
            <?php }
            }

            public function insert($num, $title) {

                $task = array(':number' => $num,
                    ':date' => "now()",
                    ':location' => "Earth",
                    ':title' => $title);

                $sql = "INSERT INTO testTable (
                            numOfSomething,
                            date,
                            location,
                            title
                        )
                        Values (
                            :number,
                            :date,
                            :location,
                            :title
                        )";
                ?><div>test 2</div><?php

                $q = $this->pdo->prepare($sql);
                ?><div>test 3</div><?php
                return $q->execute($task);
            }
        }

        $num = 20;
        $title = "This is the second row";
        $object = new insertData($host, $dbname, $username, $password);
        ?><div>Test</div><?php
        $object->test();
        $object->insert($num, $title);
    ?>
    <div>Added Successfully</div>
</body>

</html>