<!DOCTYPE html>
<html>
    <head>
        <title>Trip Booked</title>
    </head>
    <body>
        <?php include 'navigation.php'; ?>
        <?php 
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $servername = "mysql:host=localhost;dbname=air_ds";
                $username = "root";
                $password = "";
                $sessionUsername = $_POST['username'];
                $departure = $_POST['departure'];
                $arrival = $_POST['arrival'];
                $date = $_POST['date'];
                $passengers = $_POST['passengers'];
                $seats = $_POST['seats'];
                $seats = implode(',', $seats);
                $names = $_POST['names'];
                $names = implode(',', $names);
                $surnames = $_POST['surnames'];
                $surnames = implode(',', $surnames);
                $taxes = $_POST['tax'];
                $costs = $_POST['costs'];
                $costs = implode(',', $costs);
                try { //Import reservation in DB
                    $conn = new PDO($servername, $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO reservations (username, departure, arrival, date, passengers, seats, names, surnames, taxes, costs) VALUES (:username, :departure, :arrival, :date, :passengers, :seats, :names, :surnames, :taxes, :costs);";
                    $stmt  = $conn->prepare($sql);
                    $stmt->bindParam(':username',$sessionUsername);
                    $stmt->bindParam(':departure',$departure);
                    $stmt->bindParam(':arrival',$arrival);
                    $stmt->bindParam(':date',$date);
                    $stmt->bindParam(':passengers',$passengers);
                    $stmt->bindParam(':seats',$seats);
                    $stmt->bindParam(':names',$names);
                    $stmt->bindParam(':surnames',$surnames);
                    $stmt->bindParam(':taxes',$taxes);
                    $stmt->bindParam(':costs',$costs);
                    $stmt->execute();
                    echo "<h3>Booking Completed!</h3>";
                    echo "<a style='margin-left:20px' href='myTrips.php'>View Your Trips.</a>";
                    echo "<p></p>";
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