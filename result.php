<!DOCTYPE html>
<html>
    <head>
        <title>Result</title>
    </head>

    <body>
    <?php include 'navigation.php'; ?>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servername = "mysql:host=localhost;dbname=air_ds";
            $username = "root";
            $password = "";
            if(isset($_POST['name'])) { // Register
                $name = $_POST['name'];
                $surname = $_POST['surname'];
                $registerUsername = $_POST['username'];
                $registerPassword = $_POST['password'];
                $email = $_POST['email'];
                try {
                    $conn = new PDO($servername, $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
                    $stmt->bindParam(':username', $registerUsername);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($result)) { // Not unique username
                        echo "<h3>Username already taken. Please try to register again.</h3>";
                        echo "<a style='margin-left:20px;' href='login.php'>Back to Register</a>";
                    }
                    else {
                        try { // Unique username so add user in DB
                            $sql = "INSERT INTO users (name, surname, username, password, email) VALUES (:name, :surname, :registerUsername, :registerPassword, :email);";
                            $stmt  = $conn->prepare($sql);
                            $stmt->bindParam(':name',$name);
                            $stmt->bindParam(':surname',$surname);
                            $stmt->bindParam(':registerUsername',$registerUsername);
                            $stmt->bindParam(':registerPassword',$registerPassword);
                            $stmt->bindParam(':email',$email);
                            $stmt->execute();
                            echo "<h3>Successful registration. You can now log in.</h3>";
                        } catch(PDOException $e) {
                            echo "Connection failed: " . $e->getMessage();
                        }
                        echo "<a style='margin-left:20px;' href='login.php'>Back to Login</a>";
                    }
                } catch(PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
            else  { // Login
                $loginUsername = $_POST['username'];
                $loginPassword = $_POST['password'];
                try {
                    $conn = new PDO($servername, $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
                    $stmt->bindParam(':username', $loginUsername);
                    $stmt->bindParam(':password', $loginPassword);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($result)) { // Wrong Credentials
                        echo "<h3>Wrong credentials. Please try to log in again.</h3>";
                        echo "<a style='margin-left:20px;' href='login.php'>Back to Login</a>";
                    }
                    else { // Right Credentials
                        $_SESSION['username'] = $loginUsername;
                        echo "<h3>You have successfully logged in!</h3>";
                        echo "<a style='margin-left:20px;' href='home.php'>Back to Home</a>";
                        setcookie('username', $loginUsername, time() + 86400 , "/Air_DS_Website");
                        setcookie('password', $loginPassword, time() + 86400, "/Air_DS_Website");
                    }
                } catch(PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
            echo "<p></p>";
        }
        ?>
    </body>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</html>