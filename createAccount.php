<?php
/*Hanterar skapande av konto. */
    session_start();
    require 'config.php';

    $error = "";
      // Kollar om formuläret har skickats
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        // Kollar om e-post eller användarnamn redan finns i databasen, case-insensitivet. Om det finns, sätts ett felmeddelande. Om inte så skapas användaren och sessionen startas.
        $emailCheck = $pdo->prepare("SELECT COUNT(*) FROM User WHERE email = :email COLLATE NOCASE");
        $emailCheck->execute([':email' => $email]);
        $emailExists = $emailCheck->fetchColumn();

        $userCheck = $pdo->prepare("SELECT COUNT(*) FROM User WHERE username = :username COLLATE NOCASE");
        $userCheck->execute([':username' => $username]);
        $userExists = $userCheck->fetchColumn();
        // Om e-post eller användarnamn redan finns sätts ett felmeddelande. Om inte så skapas användaren och sessionen startas.
        if ($emailExists > 0) {
            $error = "Email already in use";
        } else if ($userExists > 0) {
            $error = "Username already in use";
        } else {
            // Skapar användaren i databasen säkert med en prepared statement
            $stmt = $pdo->prepare("INSERT INTO User (username, email, password) VALUES (:Username, :Email, :Password)");
            $stmt->bindParam(':Username', $username);
            $stmt->bindParam(':Email', $email);
            $stmt->bindParam(':Password', $password);
            
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: mainPage.php");
                exit;
            } else {
                $error = "Something went wrong with the database.";
            }
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
    <!-- Formulär för att skapa en användare -->
        <div id="container" class="content">
            <form action="createAccount.php" method="post">
                <?php if ($error != ""): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Enter you prefered username" required minlength="4">
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" placeholder="john@smith.com" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter a password" pattern="(?=.*\d)(?=.*[a-zåäö])(?=.*[A-ZÅÄÖ]).{8,}" required title="Must contain at least one numeric value, one symbol, one lowercase letter and one uppercase letter">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" pattern="(?=.*\d)(?=.*[a-zåäö])(?=.*[A-ZÅÄÖ]).{8,}" required title="Must contain at least one numeric value, one symbol, one lowercase letter and one uppercase letter">
                <input type="submit" value="Sign up">
                 Already Have An Account?<a href="index.php"> Log in here!</a>
            </form>
        </div>
    </div>
</body>
</html>