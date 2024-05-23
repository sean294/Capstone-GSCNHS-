<?php
    include("control/controller.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/account.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- <script src="js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"> -->
    <title>Login | GSCNHS</title>
</head>
<body>
    <div class="container">
    <?php include("error-handler/error-handler.php"); ?>
        <div class="wrapper">
            <form action="index.php" method="POST">
                <div class="header">
                    <header>
                        <h2>Login Here!!</h2>
                    </header>
                </div>
                <div class="input">
                    <label for="username">Username:</label>
                    <input type="text" required value="" name="username" id="username">
                </div>
                <div class="input">
                    <label for="password">Password:</label>
                    <input type="password" id="password" value="" name="password" required>
                </div>
                <div class="btn">
                    <button type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
