<?php session_start(); ?> <!-- Start session to get username -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="topnav" id="topNavigation"> <!-- Buttons -->
            <a href="home.php">Home</a>
            <a href="myTrips.php">My Trips</a>
            <a href="<?= isset($_SESSION['username']) ? 'logout.php' : 'login.php' ?>"><?= isset($_SESSION['username']) ? 'Logout' : 'Login' ?></a>
            <a class="icon" onclick="responsive()">
                <span style="font-size: 15px;">&#9776;</span>
            </a>
        </div>

        <script>
            function responsive() { //Change to hambuger menu
              var x = document.getElementById("topNavigation");
              if (x.className === "topnav") {
                x.className += " responsive";
              } else {
                x.className = "topnav";
              }
            }
        </script>
    </body>
</html>