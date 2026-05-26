<?php
/*Hanterar borttagning av en aktivitet. Den startar en session, inkluderar nödvändiga filer och kontrollerar om användaren är inloggad. Om en aktivitetID 
inte är angiven i URL:en, omdirigeras användaren tillbaka till huvudsidan. Om en aktivitetID är angiven, försöker den ta bort aktiviteten från databasen */
session_start();
require 'loggedInCheck.php';
require 'config.php';

if (!isset($_GET['id'])) {
    header("Location: mainPage.php");
    exit;
}

$eventID = $_GET['id'];
// Försöker ta bort aktiviteten från databasen säkert med en prepared statement.
try {
    $stmt = $pdo->prepare("DELETE FROM Event WHERE eventID = :eventID");
    $stmt->execute([':eventID' => $eventID]);

    header("Location: mainPage.php");
    exit;

} catch (PDOException $e) {
    header("Location: mainPage.php");
    exit;
}
?>