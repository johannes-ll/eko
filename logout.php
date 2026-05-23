<?php
/*Hanterar utloggning av användare. Startar en session och förstör den. */
session_start();
session_destroy();
header("Location: index.php");
exit;
?>
