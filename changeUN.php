<?php
    session_start();
    require 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newUsername = $_POST['Username'];
        $userId = $_SESSION['user_id'];

        $stmt = $pdo->prepare("UPDATE User SET username = :username WHERE id = :id");
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $_SESSION['username'] = $newUsername;

        header("Location: mainPage.php");
        exit;
    }
?>