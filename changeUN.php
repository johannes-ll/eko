<?php
/*Hanterar ändring av användarnamn. Användaren måste vara inloggad för att kunna ändra sitt användarnamn.*/
    session_start();
    require 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newUsername = $_POST['Username'];
        $userId = $_SESSION['user_id'];
        // Uppdaterar det nya användarnamnet i databasen säkert med en prepared statement
        $stmt = $pdo->prepare("UPDATE User SET username = :username WHERE userID = :id");
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $_SESSION['username'] = $newUsername;

        header("Location: mainPage.php");
        exit;
    }
?>