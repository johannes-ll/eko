<?php
/*Hanterar visning av delete-knappen. Endast administratörer och författaren av eventet kan se delete-knappen. För att avgöra detta
startas en session och användarens roll och ID jämförs med eventets författar-ID som skickas via GET-parametern. 
Om användaren är admin eller är författaren, visas delete-knappen. Annars visas ingen knapp.*/
session_start();

$authorId = $_GET['authorId'];
$eventID = $_GET['eventID'];

if (
    isset($_SESSION['role'], $_SESSION['user_id']) &&
    (
        $_SESSION['role'] === 'admin' ||
        $_SESSION['user_id'] == $authorId
    )
) {
    // Visa delete-knappen, har en onclick-event som först visar en bekräftelse-dialog. Om användaren bekräftar, omdirigeras de till deleteEvent.php med eventets ID som parameter.
    echo '<button onclick="if(confirm(\'Är du säker på att du vill ta bort detta event?\')) { window.location.href=\'deleteEvent.php?id=' . $eventID . '\'; }">Delete</button>';
}