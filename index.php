<?php
    session_start();
    require 'config.php';

    $error = "";
      
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
          
        $username = $_POST['username'];
        $password = $_POST['password'];
          
        $stmt = $pdo->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
          
        if ($user && password_verify($password, $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];
          header("Location: mainPage.php");
          exit;
          } else {
            $error = "Invalid username or password.";
          }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="c">
        <header>
            <h1>Fly från Eko</h1>
        </header>
        <div id="container" class="content">
            <form action="index.php" method="post">
            <?php if ($error != ""): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required minlength="4">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-zåäö])(?=.*[A-ZÅÄÖ]).{8,}" required title="Must contain at least one numeric value, one lowercase letter and one uppercase letter">
                <input type="submit" value="Log in">
                Don't Have An Account? <a href="createAccount.php">Sign up here!</a>
            </form>
        </div>
    </div>
</body>
</html>