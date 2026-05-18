<?php
    session_start();
    require 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = $_POST['Password'];
        $userId = $_SESSION['user_id'];

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE User SET password = :password WHERE userID = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        header("Location: mainPage.php");
        exit;
    }
?>