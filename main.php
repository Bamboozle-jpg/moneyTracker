<!-- Type usr/local/mysql/bin/mysql -u root -p to access mysql in CL -->

<!DOCTYPE html>
<html>
<head>
<style>
    body {background-color: #232323;}
    #mainDisplay {
        color: rgb(227, 244, 255); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size:40px;     
        font-weight: 600; 
        background-color: #26263b;
        padding: 10px;
        padding-right: 30px;
        padding-left: 20px;
        max-width: fit-content;
        margin: 5px;
        margin-left: 10px;
        box-sizing:content-box;
        box-shadow: -5px 5px 15px rgb(21, 30, 34);
        border-radius: 30px;
        display: inline-block;
    }

    #earnSpend {
        color: rgb(238, 227, 255); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size:40px;     
        font-weight: 600; 
        background-color: #3B263B;
        padding: 10px;
        padding-right: 30px;
        padding-left: 20px;
        max-width: fit-content;
        margin: 5px;
        margin-left: 10px;
        margin-top: 40px;
        box-sizing:content-box;
        box-shadow: -5px 5px 15px rgb(21, 30, 34);
        border-radius: 30px;
        display: inline-block;
    }

    #title {
        color: rgb(138, 165, 193); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size: 25px; 
        font-weight: 900; 
        margin-top: 35px;
        margin-left: 35px;
    }

    #amount {
        color: rgb(196, 215, 221); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size: 25px; 
        font-weight: 200; 
        margin-top: 10px;
        margin-left: 35px;
        margin-right: 5px;
        margin-bottom: 0px;
        padding-right: 30px;
        width: 150px;
        display: inline-block;
    }

    #date {
        color: rgb(196, 215, 221); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size: 25px; 
        font-weight: 200; 
        margin-top: 10px;
        margin-left: 35px;
        margin-right: 0px;
        margin-bottom: 0px;
        padding-right: 30px;
        display: inline-block;
        width: 200px
    }

    #location {
        color: rgb(196, 215, 221); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size: 25px; 
        font-weight: 200; 
        margin-top: 10px;
        margin-left: 10px;
        margin-right: 35px;
        margin-bottom: 0px;
        padding-right: 30px;
        display: inline-block;
        width: 200px
    }

    #description {
        color: rgb(196, 215, 221); 
        font-family: sans-serif; 
        font-style: italic; 
        font-size: 25px; 
        font-weight: 200; 
        margin-top: 10px;
        margin-left: 35px;
        margin-right: 35px;
        margin-bottom: 0px;
        padding-right: 30px;
    }

    #line1 {
        height: 10px;
        margin-right: 35px;
        max-width: 1000px;
        margin-left: 65px;
        margin-top: 20px;
        margin-bottom: -15px;
        background: rgb(60, 67, 91);
        box-shadow: -6px 6px #070c24 ;
    }

    #lineTitle {
        height: 10px;
        margin-right: 65px;
        max-width: 10000px;
        margin-left: 65px;
        margin-top: 20px;
        margin-bottom: -15px;
        background: #4B364F;
        box-shadow: -6px 6px #2A0A35 ;
    }

</style>
<title>Test Page</title>
</head>

<body>
    <div id="mainDisplay">Total : $8,018.53</div>
    <div id="mainDisplay">Savings : $7,018.00</div>
    <div id="mainDisplay">Free Spend : $1,000.53</div>
    <div id="lineTitle"></div>
    <div id="earnSpend"><a href="/moneyTracker/earn.php"; style="color: rgb(238, 227, 255); text-decoration: none">EARN</a></div>
    <div id="earnSpend"><a href="/moneyTracker/spend.php"; style="color: rgb(238, 227, 255); text-decoration: none">SPEND</a></div>

    <div id="title">Description of what I used it for</div>
    <div id="description">Info Here $57.35</div>
    <div id="line1"></div>
    
    <?php
        include 'dbconfig.php';

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
                <div id='location'> <?php echo sprintf('%s <br/>', $r['location']); ?></div>
                <div id='description'> <?php echo sprintf('%s', $r['description']); ?></div>
                <div id="line1"></div>
            <?php }
        } catch (PDOException $pe) {
            die("Could not connect to the database $dbname :" . $pe->getMessage());
        }
    ?>

    <div id="title">Second description of what I used it for</div>
    <div id="description">More info Here $32.35</div>
    <div id="line1"></div>
    <div id="title">Third description of what I used it for</div>
    <div id="description">Even more info Here $21.35</div>
    <div id="line1"></div>
</body>

</html>