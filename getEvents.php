<?php
require 'config.php';
$query = "SELECT * FROM Event";
$content = $pdo->prepare($query);
$content->execute();
while($row = $content->fetch(PDO::FETCH_ASSOC)) {
    echo $row['title'] . " " . $row['info'] . " " . $row['date'] . " " . $row['time'] . " " . $row['longitude'] . " " . $row['latitude'] . "<br>";
}
?>