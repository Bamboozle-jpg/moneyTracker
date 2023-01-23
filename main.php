<!-- Type usr/local/mysql/bin/mysql -u root -p to access mysql in CL -->

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="stylesheet.css">
<title>Test Page</title>
</head>

<body>
    <?php
        include 'dbconfig.php';

        $total = 0;     //Calculated
        $savings = 0;   //ID = 0
        $freeSpend = 0; //Calculated
        $wallet = 0;    //ID = 1
        $drawer = 0;    //ID = 2
        $checking = 0;  //ID = 3
        $venmo = 0;     //ID = 4

        try {
            $connection = mysqli_connect($host, $username, "", $dbname);
            $query = "SELECT id FROM money";
            $result = mysqli_query($connection, $query);
            $count = mysqli_num_rows($result);
            mysqli_close($connection);

            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $sql = 'SELECT location, amount
                FROM money
                ORDER BY location DESC';
            $q = $conn->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);

            while ($r = $q->fetch()) {
                if ($r['location'] == 0) {
                    $savings += $r['amount'];
                } elseif ($r['location'] == 1) {
                    $wallet += $r['amount'];
                } elseif ($r['location'] == 2) {
                    $drawer += $r['amount'];
                } elseif ($r['location'] == 3) {
                    $checking += $r['amount'];
                } elseif ($r['location'] == 4) {
                    $venmo += $r['amount'];
                }
                // "$ ".number_format($savings, 2)
            }
        } catch (PDOException $pe) {
            die("Could not connect to the database $dbname :" . $pe->getMessage());
        }

        $freeSpend += $wallet;
        $freeSpend += $drawer;
        $freeSpend += $checking;
        $freeSpend += $venmo;
        $total += $freeSpend;
        $total += $savings;
    ?>
    <div id="mainDisplay">Total : <?php echo "$ ".number_format($total, 2) ?></div>
    <div id="mainDisplay">Savings : <?php echo "$ ".number_format($savings, 2) ?></div>
    <div id="mainDisplay">Free Spend : <?php echo "$ ".number_format($freeSpend, 2) ?></div>
    <div id="optionalDisplay">Wallet : <?php echo "$ ".number_format($wallet, 2) ?></div>
    <div id="optionalDisplay">Drawer : <?php echo "$ ".number_format($drawer, 2) ?></div>
    <div id="optionalDisplay">Checking : <?php echo "$ ".number_format($checking, 2) ?></div>
    <div id="optionalDisplay">Venmo : <?php echo "$ ".number_format($venmo, 2) ?></div>
    <div id="lineTitle"></div>
    <div id="earnSpend"><a href="/moneyTracker/earn.php"; style="color: rgb(238, 227, 255); text-decoration: none">EARN</a></div>
    <div id="earnSpend"><a href="/moneyTracker/spend.php"; style="color: rgb(238, 227, 255); text-decoration: none">SPEND</a></div>
    <button id="earnSpend" onclick="myFunction()">Show/Hide Details</button>
    <script>

        function myFunction() {

            var elms = document.querySelectorAll("[id='optionalDisplay']");
            for(var i = 0; i < elms.length; i++) {
                if (elms[i].style.display === "none") {
                    elms[i].style.display = "inline-block";
                } else {
                    elms[i].style.display = "none";
                }
            }
        }

        function openPage($test) {
            if ($test == 15) {
                var elms = document.querySelectorAll("[id='optionalDisplay']");
                for(var i = 0; i < elms.length; i++) {
                    if (elms[i].style.display === "none") {
                        elms[i].style.display = "inline-block";
                    } else {
                        elms[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <?php

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $sql = 'SELECT id, title, description, location, date, amount
                FROM money
                ORDER BY id DESC';
            $q = $conn->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);

            while ($r = $q->fetch()) { ?>
                <div id='title'> <?php echo sprintf('%s <br/>', $r['title']); ?></div>
                <div id='amount'> <?php echo "$ ".number_format($r['amount'], 2); ?></div>
                <?php 
                        $year = substr($r['date'], 0, 4);
                        $month = substr($r['date'], 5, -3);
                        $day = substr($r['date'], 8, 9);
                        $dateTemp = $month;
                        $dateTemp .= "/";
                        $dateTemp = $day;
                        $dateTemp .= "/";
                        $dateTemp = $year;
                        $date = date("F j, Y", strtotime($dateTemp)); 
                ?>
                <div id='date'> <?php echo sprintf('%s <br/>', $date); ?></div>
                <div id='location'> <?php 
                    if ($r['location'] == 0) {
                        echo sprintf('%s <br/>', "Savings"); 
                    } elseif ($r['location'] == 1) {
                        echo sprintf('%s <br/>', "Wallet"); 
                    } elseif ($r['location'] == 2) {
                        echo sprintf('%s <br/>', "Drawer"); 
                    } elseif ($r['location'] == 3) {
                        echo sprintf('%s <br/>', "Checking"); 
                    } elseif ($r['location'] == 4) {
                        echo sprintf('%s <br/>', "Venmo"); 
                    }
                ?></div>
                <?php
                    if ($r['id'] == $count) {
                        ?>  <a id='X' class="button" href="#popupDel">❌</a>  <?php
                    } 
                ?>
                <div id='description'> <?php echo $r['id'] ?> </div>
                <div id='description'> <?php echo sprintf('%s', $r['description']); ?></div>
                <div id="line1"></div>
            <?php }
        } catch (PDOException $pe) {
            die("Could not connect to the database $dbname :" . $pe->getMessage());
        }
    ?>

<div id="popupDel" class="overlay">
    <div class="popup">
        <h2><?php echo $r['id']?></h2>
        <a class="close" href="#">&times;</a>
        <div class="content">
            Thank to pop me out of that button, but now i'm done so you can close this window.
        </div>
    </div>
</div>
</body>

</html>