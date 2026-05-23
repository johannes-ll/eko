<?php
/*Denna fil används för att kontrollera om en användare är inloggad. Den startar en session och kontrollerar om 'user_id' är satt i sessionen.*/
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>