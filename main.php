<!-- Type usr/local/mysql/bin/mysql -u root -p to access mysql in CL -->

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="stylesheet.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js">
</script>
<style>
.overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(5, 0, 0, 0.7);
    transition: opacity 500ms;
    visibility: hidden;
    opacity: 0;
    transition: all .5s ease-in-out;
}
    .overlay:target {
        visibility: visible;
        opacity: 1;
    }

.popup {
    margin: 70px auto;
    padding: 20px;
    background: #232323;
    border-radius: 5px;
    width: 90%;
    position: relative;
    transition: all 5s ease-in-out;
}
    .popup h2 {
        margin-top: 0;
        color: rgb(196, 215, 221);
        font-family: Tahoma, Arial, sans-serif;
    }

    .popup .close {
        position: absolute;
        top: 20px;
        right: 30px;
        transition: all 200ms;
        font-size: 30px;
        font-weight: bold;
        text-decoration: none;
        color: rgb(196, 215, 221);
    }
    .popup .close:hover {
        color: rgb(240, 240, 240);
    }
    .popup .content {
        max-height: 60%;
        overflow: auto;
    }

</style>
<title>Test Page</title>
</head>

<body>
    <?php
        include 'dbconfig.php';
        // include 'deleteItem.php';

        $total = 0;     //Calculated
        $savings = 0;   //ID = 0
        $freeSpend = 0; //Calculated
        $wallet = 0;    //ID = 1
        $drawer = 0;    //ID = 2
        $checking = 0;  //ID = 3
        $venmo = 0;     //ID = 4
        $firstOpen = 1;
        $highestID = 0; //This is the ID of the actual thing itself

        try {
            $connection = mysqli_connect($host, $username, "", $dbname);
            $query = "SELECT id FROM money";
            $result = mysqli_query($connection, $query);
            $count = mysqli_num_rows($result);
            mysqli_close($connection);

            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $sql = 'SELECT id, location, amount
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

                //Makes sure highestID is pointing to the highest ID
                if ($r['id'] > $highestID) {
                    $highestID = $r['id'];
                }
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
    <button id="earnSpend" onclick="displayToggle()">Show/Hide Details</button>
    <script>
        function displayToggle() {
            var elms = document.querySelectorAll("[id='optionalDisplay']");
            for(var i = 0; i < elms.length; i++) {
                if (elms[i].style.display === "none") {
                    elms[i].style.display = "inline-block";
                } else {
                    elms[i].style.display = "none";
                }
            }
        }

        //Called by Final delete button to call deleteItem.php to remove top item
        function delTop(test) {
            x = <?php echo $highestID ?>;
            try {
                $.ajax({
                    url : 'deleteItem.php',
                    type : 'POST',
                    data : { id : x },
                    success : function (result) {
                        location.href = 'main.php#';
                        location.reload();
                    },
                    error : function (err) {
                        alert(err)
                    }
                });
            } catch(err) {
                alert(err.message);
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
                <?php if ($r['amount'] > 0) { ?>  <div id='amount-green'> <?php echo "$ ".number_format($r['amount'], 2); ?></div> 
                <?php } else { ?>  <div id='amount-red'> <?php echo "$ ".number_format($r['amount'], 2); ?></div> <?php } ?>
                <?php 
                    //Prints the date
                    $year = substr($r['date'], 0, 4);
                    $month = substr($r['date'], 5, -3);
                    $day = substr($r['date'], 8, 10);
                    $dateTemp = $month;
                    $dateTemp .= "/";
                    $dateTemp .= $day;
                    $dateTemp .= "/";
                    $dateTemp .= $year;
                    $date = date("F j, Y", strtotime($dateTemp)); 
                ?>

                <div id='date'> <?php echo sprintf('%s <br/>', $date); ?></div>
                <div id='location'> <?php 
                    //prints the location
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
                    //Puts a X by the top of the list
                    if ($r['id'] == $highestID) {
                        ?>  <a id='X' class="button" href="#popupDel">X</a>  <?php
                    }
                ?>

                <div id='description'> <?php echo sprintf('%s', $r['description']); ?></div>
                <div id="line1"></div>
            <?php }
        } catch (PDOException $pe) {
            die("Could not connect to the database $dbname :" . $pe->getMessage());
        }
    ?>

<div id="popupDel" class="overlay">
    <div class="popup">
        <a class="close" href="#">&times;</a>
        <div class="content">
            <?php            
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $sql = 'SELECT id, title, description, location, date, amount
                FROM money
                ORDER BY id DESC';
            $q = $conn->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);

            while ($r = $q->fetch()) { 
            if ($r['id'] == $highestID) { 

                $highestID = $r['id']; ?>
                <div id='pTitle'>ARE YOU SURE YOU WANT TO DELETE THIS ENTRY?</div>
                <div id='title'> <?php echo sprintf('%s <br/>', $r['title']); ?></div>
                <?php if ($r['amount'] > 0) { ?>  <div id='amount-green'> <?php echo "$ ".number_format($r['amount'], 2); ?></div> 
                <?php } else { ?>  <div id='amount-red'> <?php echo "$ ".number_format($r['amount'], 2); ?></div> <?php } ?>
                <?php 
                        $year = substr($r['date'], 0, 4);
                        $month = substr($r['date'], 5, -3);
                        $day = substr($r['date'], 8, 10);
                        $dateTemp = $month;
                        $dateTemp .= "/";
                        $dateTemp .= $day;
                        $dateTemp .= "/";
                        $dateTemp .= $year;
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
                    echo printf('First open : %b. <br/>', $firstOpen);
                ?></div>
                <div id='description'> <?php echo sprintf('%s', $r['description']); ?></div>
                <button id="delEntry" onclick="delTop(<?php echo $highestID ?>)">Delete</button>
                <a id="cancelDel" href="#">Cancel</a>
            <?php }} ?>
        </div>
    </div>
</div>
</body>
</html>