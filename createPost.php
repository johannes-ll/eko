<?php
session_start();
require 'loggedInCheck.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $info = $_POST["info"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];

    $stmt = $pdo->prepare("INSERT INTO Event (title, info, date, time, longitude, latitude, userID) VALUES (:Title, :Info, :Date, :Time, :Longitude, :Latitude, :UserID)");
    $stmt->bindParam(':Title', $title);
    $stmt->bindParam(':Info', $info);
    $stmt->bindParam(':Date', $date);
    $stmt->bindParam(':Time', $time);
    $stmt->bindParam(':Longitude', $longitude);
    $stmt->bindParam(':Latitude', $latitude);
    $stmt->bindParam(':UserID', $_SESSION['user_id']);
    $stmt->execute();
}