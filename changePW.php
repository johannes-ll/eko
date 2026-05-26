<?php
/*Hanterar ändring av lösenord. Användaren måste vara inloggad för att kunna ändra sitt lösenord.*/
    session_start();
    require 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = $_POST['Password'];
        $userId = $_SESSION['user_id'];
        // Hashar det nya lösenordet innan det sparas i databasen
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // Uppdaterar det nya lösenordet i databasen säkert med en prepared statement
        $stmt = $pdo->prepare("UPDATE User SET password = :password WHERE userID = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        header("Location: mainPage.php");
        exit;
    }
?>