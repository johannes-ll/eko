<?php
/*Hanterar skapande av event. Användaren måste vara inloggad för att kunna skapa ett event.*/
session_start();
require 'loggedInCheck.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $title = $_POST["title"];
    $info = $_POST["info"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $adress = $_POST["adress"];
/*För att få latitud och longitud från adressen, används Nominatim API från OpenStreetMap. Adressen kodas först för att säkerställa att den är korrekt formaterad för URL:en. 
Sedan görs en HTTP-förfrågan till API:et, och svaret dekodas från JSON-format. Om adressen hittas skapas latitud och longitud som sedan används  i SQL-frågan för att skapa det nya eventet i databasen.
Om adressen inte hittas, visas ett felmeddelande.*/
    $safeAddress = urlencode($adress);

    $url = "https://nominatim.openstreetmap.org/search?q={$safeAddress}&format=json&limit=1";

    $options = [
        "http" => [
            "header" => "User-Agent: FlyFranEko/1.0\r\n"
        ]
    ];
    $context = stream_context_create($options);

    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);

    if (!empty($data)) {
        $latitude = $data[0]['lat'];
        $longitude = $data[0]['lon'];

        $stmt = $pdo->prepare("INSERT INTO Event (title, info, date, time, longitude, latitude, adress, userID) VALUES (:Title, :Info, :Date, :Time, :Longitude, :Latitude, :Adress, :UserID)");
        $stmt->bindParam(':Title', $title);
        $stmt->bindParam(':Info', $info);
        $stmt->bindParam(':Date', $date);
        $stmt->bindParam(':Time', $time);
        $stmt->bindParam(':Longitude', $longitude);
        $stmt->bindParam(':Latitude', $latitude);
        $stmt->bindParam(':Adress', $adress);
        $stmt->bindParam(':UserID', $_SESSION['user_id']);
        $stmt->execute();
        header("Location: mainPage.php");
        exit;
    }
    else {
    echo "Kunde inte hitta adressen, försök vara mer specifik (t.ex. lägg till ', Uppsala').";}
}
?>
<doctype html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fly från Eko</title>
    <link rel="stylesheet" href="style.css">    
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="c">
 <header>
        <h1>Fly från Eko</h1>
        <nav>
            <a href="mainPage.php">Till startsidan</a>
        </nav>
    </header>
    <!-- Formulär för att skapa ett event -->
    <div id="container" class="content">
    <form action="createPost.php" method="post">
        <label for="title">Titel:</label>
        <input type="text" name="title" placeholder="Tite på ditt event" id="title" required>
        
        <label for="info">Information:</label>
        <textarea name="info" id="info" placeholder="Beskriv ditt event" required></textarea>
        
        <label for="date">Date:</label>
        <input type="text" name="date" id="date" placeholder="ddmmyy" required>
        
        <label for="time">Time:</label>
        <input type="string" name="time" id="time" placeholder="00:00"required>
        
        <label for="adres">Adress:</label>
        <input type="text" name="adress" id="adress" placeholder="Ex Studentvägen 1, Uppsala"required>

        <input type="submit" value="Create Post">
    </form>
    </div>
    </div>
</body>
</html>