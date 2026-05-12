<?php
    session_start();
    require 'config.php';
    require 'loggedInCheck.php';
    $isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventutskottet</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>Fly från Eko</h1>
        <nav>
        <?php if ($isLoggedIn): ?>
            <a href="logout.php"> <br> Logga ut </a>
            <a href="mainPage.php">Till startsidan</a>
        <?php endif; ?>
        </nav>
    </header>
     <div>
        <form class="content" action="changeUN.php" method="POST">
            <label for="Username">Nytt användarnamn:</label>
            <textarea name="Username" placeholder="Skriv ditt nya användarnamn..." required minlength="4" required></textarea>
            <button type="submit">Skicka</button>
        </form>
        <form class="content" action="changePW.php" method="POST">
            <label for="Password">Nytt lösenord:</label>
            <textarea name="Password" placeholder="Skriv ditt nya lösenord..." pattern="(?=.*\d)(?=.*[a-zåäö])(?=.*[A-ZÅÄÖ]).{8,}" required title="Must contain at least one numeric value, one lowercase letter and one uppercase letter" required></textarea>
            <button type="submit">Skicka</button>
        </form>
     </div>
</body>
</html>