<!DOCTYPE html>
<html>
    <head>
        <title>Trip Cancelled</title>
    </head>
    <body>
    <?php include 'navigation.php'; ?>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servername = "mysql:host=localhost;dbname=air_ds";
            $username = "root";
            $password = "";
            $id = $_POST['id'];
            try { // Cancel Trip
                $conn = new PDO($servername, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM reservations WHERE id = :id";
                $stmt  = $conn->prepare($sql);
                $stmt->bindParam(':id',$id);
                $stmt->execute();
                echo "<h3>Trip successfully cancelled.</h3>";
                echo "<a style='margin-left:20px;' href='myTrips.php'>Back to My Trips</a>";
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