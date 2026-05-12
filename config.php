<?php
      $db_file = 'FlyEko.db';
      
      try {
          $pdo = new PDO("sqlite:" . $db_file);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
      } catch (PDOException $e) {
          die("Kunde inte ansluta till databasen $db_file: " . $e->getMessage());
      }
?>