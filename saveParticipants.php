<?php
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

/*RÄKNAR UT HUR MÅNGA SOM KOMMER
$stmt = $pdo->prepare("SELECT COUNT(*) FROM EventParticipant WHERE eventID = :eventID");
$stmt->execute([':eventID' => $eventID]);
$totalAttending = $stmt->fetchColumn();*/
?>