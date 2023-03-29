<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="stylesheetEarn.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js">
</script>
<style>
    body {background-color: #232323;}
</style>
<title>Earn Page</title>
</head>

<body>
    <div id="title">Add Money to Account</div>
    <div id="lineTitle"></div>
    <div id="errorDiv" hidden="hidden">Please make sure you've given a title, valid amount, location and description, then try again.</div>
    <div id="verticalContainer">
        <div id="horizontalContainer">
            <div id="subtitle">Title : </div>
            <input type="text" id="titleInput" placeholder="ex. Babysitting">
        </div>
        <div id="horizontalContainer">
            <div id="subtitle">Amount : </div>
            <div id = "inside">
                <div id="subtitleMoney">$ </div>
                <input type="text" id="amountInput" placeholder="ex. 35.40">
            </div>
        </div>
        <div id="horizontalContainer">
            <div id="subtitle">Location : </div>
            <select name="locationInput" id="locationInput">
                <option value=5>Select Location ▼</option>
                <option value=0>Savings</option>
                <option value=1>Wallet</option>
                <option value=2>Drawer</option>
                <option value=3>Checking</option>
                <option value=4>Venmo</option>
        </select>
        </div>
        <div id="verticalContainerInside">
            <div id="subtitleCentered">Description : </div>
            <input type="text" id="descriptionInput" placeholder="ex. Babysat for John for 1.5 hours">
        </div>
    </div>
    <div id="buttonHolder">
        <button id="button" onclick="myFunction()">ADD MONEY</button>
        <a id='backToMain' href='/moneyTracker/main.php'>BACK</a>
    </div>
</body>

<script>
    function myFunction() {
        var Amount = document.getElementById("amountInput").value;
        var Title = document.getElementById("titleInput").value;
        var Location = document.getElementById("locationInput").value;
        var Description = document.getElementById("descriptionInput").value;

        let hiddenDiv = document.getElementById("errorDiv");
        let hidden = hiddenDiv.getAttribute("hidden");

        if (!Amount || isNaN(Amount)) {
            hiddenDiv.removeAttribute("hidden");
        } else if (!Title) {
            hiddenDiv.removeAttribute("hidden");
        } else if (!Description) {
            hiddenDiv.removeAttribute("hidden");
        } else if (Location == 5) {
            hiddenDiv.removeAttribute("hidden");
        } else {
            try {
                $.ajax({
                    url : 'insertItem.php',
                    type : 'POST',
                    data : { amount : Amount, title : Title, location : Location, description : Description},
                    success : function (result) {
                        location.href = '/moneyTracker/main.php';
                        // location.reload();
                    },
                    error : function (err) {
                        alert(err.message)
                    }
                });
            } catch(err) {
                alert(err.message);
            }
        }
    }
</script>

</html>