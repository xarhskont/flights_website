<!DOCTYPE html>
<html>
    <head>
        <title>My Trips</title>
    </head>
    <body>
        <?php include 'navigation.php'; ?>
        <?php
            if(!isset($_SESSION['username'])) { //Not logged in
                echo "<h2>You need to be logged in to view your trips.</h2>";
            }
            else { //Logged in
                $servername = "mysql:host=localhost;dbname=air_ds";
                $username = "root";
                $password = "";
                $sessionUsername = $_SESSION['username'];
                try { // Search Trips
                    $conn = new PDO($servername, $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM reservations WHERE username = :username");
                    $stmt->bindParam(':username', $sessionUsername);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($result)) { // No Trips
                        echo "<h2>You don't have active reservations.</h2>";
                    }
                    else { // Trips
                        echo "<h2>My trips:</h2>";
                        $count = 0;
                        foreach($result as $row) { //Show info of each trip
                            $today = time();
                            $date = strtotime($row['date']);
                            if(floor(($date - $today) / 86400) >= 0) {
                            $count++;
                            echo "<h3>Trip " . htmlspecialchars($count) . "</h3>";
                            echo "<p style='margin-left:20px;'>Departure: " . htmlspecialchars($row['departure']) . "</p>";
                            echo "<p style='margin-left:20px;'>Arrival: " . htmlspecialchars($row['arrival']) . "</p>";
                            echo "<p style='margin-left:20px'>Date: " . htmlspecialchars($row['date']) . "</p>";
                            echo "<p style='margin-left:20px'>No of passengers: " . htmlspecialchars($row['passengers']) . "</p>";
                            $names = explode(",",$row['names']);
                            $surnames = explode(",",$row['surnames']);
                            $costs = explode(",",$row['costs']);
                            for ($i = 0; $i < count($names); $i++) {
                                echo "<p style='margin-left:20px'>Passenger " . htmlspecialchars($i+1) . " Fullname: " . htmlspecialchars($names[$i]) . " " . htmlspecialchars($surnames[$i]) . "</p>";
                                echo "<p style='margin-left:20px'>Passenger " . htmlspecialchars($i+1) . " Ticket Price: " . htmlspecialchars($costs[$i]) . "€</p>";
                            }
                            echo "<p style='margin-left:20px'>Taxes: " . htmlspecialchars($row['taxes']) . "€</p>";
                            if(floor(($date - $today) / 86400) >= 0) {
                                echo "<form action='cancel.php' method='POST'>";
                                echo "<input style='display:none' type='text' name='id' value='" . htmlspecialchars($row['id']) . "'></input>";
                                echo "<input type='submit' id='cancel' value='Cancel Reservation'></input>";
                                echo "</form>";
                            }
                            else echo "<button style='margin-left:17px;' id='cancelBtn' disabled>Cancel Reservation</button>";
                            }
                        }

                    }
                } catch(PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
        ?>
    </body>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</html>