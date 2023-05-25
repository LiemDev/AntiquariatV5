<?php
// Startet die Session oder setzt sie fort, wenn bereits eine Sitzung existiert
session_start();

// Prüft, ob die Session-Variable 'loggedin' existiert und auf true gesetzt ist
if (isset($_SESSION['loggedin'])) {
  // Wenn die Variable 'loggedin' auf true gesetzt ist, wird die Session zerstört
  session_destroy();

  // Der Benutzer wird zur Startseite der Anwendung weitergeleitet
  header("Location: index.php");
} else {
  // Wenn die Variable 'loggedin' nicht existiert oder auf false gesetzt ist,
  // wird der Benutzer ebenfalls zur Startseite der Anwendung weitergeleitet
  header("Location: index.php");
}
?>
