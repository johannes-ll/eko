<?php
/*Hämtar alla event från databasen och räknar ut hur många som kommer att delta i varje event. Resultatet returneras som en JSON-array.*/
require 'config.php';

$query = "SELECT * FROM Event";
$content = $pdo->prepare($query);
$content->execute();

$events = [];
// Loopar igenom varje event och räknar ut hur många som kommer att delta i varje event genom att göra en separat fråga för varje eventID.
while ($row = $content->fetch(PDO::FETCH_ASSOC)) {

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM EventParticipant 
        WHERE eventID = :eventID
    ");

    $stmt->execute([
        ':eventID' => $row['eventID']
    ]);
    // Hämtar antalet deltagare för det aktuella eventet
    $totalAttending = $stmt->fetchColumn();

    $events[] = [
        "title" => $row['title'],
        "info" => $row['info'],
        "date" => $row['date'],
        "time" => $row['time'],
        "longitude" => (float)$row['longitude'],
        "latitude" => (float)$row['latitude'],
        "id" => (int)$row['eventID'],
        "userid" => (int)$row['userID'],
        "adress" => $row['adress'],
        "members" => (int)$totalAttending
    ];
}
// Sätter content type till JSON och returnerar eventen som en JSON-array.
header('Content-Type: application/json');

echo json_encode($events);
?>