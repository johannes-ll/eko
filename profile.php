<?php
/*Hanterar användarens profil. Den startar en session, inkluderar nödvändiga filer och kontrollerar om användaren är inloggad. 
Användaren kan ändra sitt användarnamn och lösenord via formulären på sidan. */
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
    <div class="c">
 <header>
        <h1>Fly från Eko</h1> <h2> <?php echo $_SESSION['username']; ?>s profil</h2>
        <nav>
            <a href="logout.php">Logga ut</a>
            <a href="mainPage.php">Till startsidan</a>
        </nav>
    </header>
     <div>
        <div class="container">
            <form class="content" action="changeUN.php" method="POST">
                <label for="Username">Nytt användarnamn:</label>
                <input name="Username" placeholder="Skriv ditt nya användarnamn..." required minlength="4" required></input>
                <button type="submit">Skicka</button>
            </form>
            <form class="content" action="changePW.php" method="POST">
                <label for="Password">Nytt lösenord:</label>
                <input type="password" name="Password" placeholder="Skriv ditt nya lösenord..." pattern="(?=.*\d)(?=.*[a-zåäö])(?=.*[A-ZÅÄÖ]).{8,}" required title="Must contain at least one numeric value, one lowercase letter and one uppercase letter" required></input>
                <button type="submit">Skicka</button>
            </form>
        </div>
     </div>
    </div>
</body>
</html>