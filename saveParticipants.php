<?php
session_start();
require 'loggedInCheck.php';
require 'config.php';

$eventID = $_GET['id']; 
$userID = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO EventParticipant (eventID, userID) VALUES (:eventID, :userID)");
    $stmt->execute([':eventID' => $eventID, ':userID' => $userID]);
    echo "Snyggt! Du är nu anmäld.";
} catch (PDOException $e) {
    echo "Du är redan anmäld till detta event!";
}

/*RÄKNAR UT HUR MÅNGA SOM KOMMER
$stmt = $pdo->prepare("SELECT COUNT(*) FROM EventParticipant WHERE eventID = :eventID");
$stmt->execute([':eventID' => $eventID]);
$totalAttending = $stmt->fetchColumn();*/
?>