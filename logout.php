<!DOCTYPE html>
<html>
    <head>
        <title>Logged out</title>
    </head>
    <body>
        <?php include 'navigation.php'; ?>
        <h3>You have successfully logged out!</h3>
        <a style="margin: 0px 0px 10px 20px;" href='login.php'>Back to Login.</a>
        <p></p>
        <?php
            session_destroy(); //Destroy session so it removes the username
        ?>
    </body>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</html>