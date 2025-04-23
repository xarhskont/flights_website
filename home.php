<?php //Get airports from DB
    $servername = "mysql:host=localhost;dbname=air_ds";
    $username = "root";
    $password = "";
    try {
        $conn = new PDO($servername, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT name FROM airports;");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
    </head>
    <body>
        <?php include 'navigation.php'; ?>
        <h2>Booking Form</h2>
        <form action="book.php" method="POST"> <!-- Booking Form -->
            <label for="from">Depart From:</label><br>
            <select name="from" id="from" required>
                <option value="">--</option>
                <?php
                if(!empty($result)) {
                    foreach ($result as $row) {
                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option></br>"; 
                    }
                }
            ?>
            </select><br>
            <label for="where">Arrive To:</label><br>
            <select name="where" id="where" required>
                <option value="">--</option>
                <?php
                if(!empty($result)) {
                    foreach ($result as $row) {
                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option></br>"; 
                    }
                }
                ?>
            </select><br>
            <label for="time">Date of Flight:</label><br>
            <input type="date" min="<?php echo date("Y-m-d"); ?>" name="time" required><br>
            <label for="count">Number of Passengers:</label><br>
            <input type="number" min="1" max="9" name="count" required><br>
            <input type="submit" id="submit" value="Search" <?= !isset($_SESSION['username']) ? 'disabled' : '' ?>><br>
        </form>
        <p id="error"><?= !isset($_SESSION['username']) ? 'You are not logged in.' : '' ?></p>

        <script>
            const from = document.getElementById("from");
            const where = document.getElementById("where");
            const error = document.getElementById("error");
            const submit = document.getElementById("submit");
            from.addEventListener("change", check);
            where.addEventListener("change", check);
            function check() { //When a selection is made check if airports are the same
                if(from.value === where.value) {
                    error.innerHTML = "Choose different airports.";
                    submit.disabled = true;
                }
                else {
                    error.innerHTML = "";
                    submit.disabled = false;
                }
            }
        </script>
    </body>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</html>