<?php
/* Denna fil hanterar när en användare anmäler sig till ett event. Den tar emot eventets ID och använder sedan den inloggade användarens ID 
från sessionen för att skapa en ny post i EventParticipant-tabellen som kopplar användaren till det specifika eventet.*/
session_start();
require 'loggedInCheck.php';
require 'config.php';

$eventID = $_GET['id']; 
$userID = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO EventParticipant (eventID, userID) VALUES (:eventID, :userID)");
    $stmt->execute([':eventID' => $eventID, ':userID' => $userID]);
    header("Location: mainPage.php");
    exit;
} catch (PDOException $e) {
    header("Location: mainPage.php");
    exit;
}
?>