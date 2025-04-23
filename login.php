<?php
    $servername = "mysql:host=localhost;dbname=air_ds";
    $username = "root";
    $password = "";

    $cookieUsername = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
    $cookiePpassword = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';

    try {
        $conn = new PDO($servername, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM users;");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <?php include 'navigation.php'; ?>
        <div style="display:flex">
        <div id="loginDiv">
        <h2>Login</h2> <!-- Login Form -->
            <form action="result.php" method="POST">
                <label for="username">Username:</label><br>
                <input type="text" name="username" value="<?= htmlspecialchars($cookieUsername) ?>" required><br>
                <label for="password">Password:</label><br>
                <input type="password" name="password" value="<?= htmlspecialchars($cookiePpassword) ?>" required><br>
                <input type="submit" id="loginBtn" value="Login">
            </form>
            <button id="goRegister" onclick="register()">Don't have an account? Register now.</button>
        </div>
        <div id="registerDiv" style="display:none;"> <!-- Register Form -->
            <h2>Register</h2>
            <form action="result.php" method="POST">
                <label for="name">Name:</label><br>
                <input type="text" name="name" pattern="[A-Za-z]{3,20}" required><br>
                <spam>It must only contain latin characters and the length be between 3 to 20 characters.</spam><br><br>
                <label for="surname">Surname:</label><br>
                <input type="text" name="surname" pattern="[A-Za-z]{3,20}" required><br>
                <spam>It must only contain latin characters and the length be between 3 to 20 characters.</spam><br><br>
                <label for="username ">Username:</label><br>
                <input type="text" name="username" id="username" required><br>
                <spam>It must be unique.</spam><br><br>
                <label for="password">Password:</label><br>
                <input type="text" name="password" pattern="(?=.*\d).{4,10}" required><br>
                <spam>It must only contain latin characters and at least one number and the length be between 4 to 10 characters.</spam><br><br>
                <label for="email">E-mail:</label><br>
                <input type="email" name="email" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" required><br>
                <spam>It must have this format "characters@characters.domain".</spam><br><br>
                <input type="submit" id="registerBtn" value="Register">
            </form>
        </div>
        </div>

        <script>
            function register() { //Show register form
                document.getElementById("loginDiv").style.display = "none";
                document.getElementById("registerDiv").style.display = "block";
            }
        </script>
    </body>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</html>