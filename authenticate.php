<?php
// Startet die Sitzung
session_start();

// Fügt die Datenbank-Konfigurationsdatei hinzu
include 'db_config.php';

// Erhalte die Benutzerdaten aus dem POST-Array
$benutzername = $_POST['benutzername'];
$passwort = $_POST['passwort'];

// Erstelle eine SQL-Abfrage, um den Benutzer in der Datenbank zu finden
$sql = "SELECT * FROM benutzer WHERE benutzername = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $benutzername);
$stmt->execute();

// Hole das Ergebnis der Abfrage
$result = $stmt->get_result();

// Überprüfe, ob ein Benutzer gefunden wurde
if ($result->num_rows > 0) {
  // Wenn ein Benutzer gefunden wurde, speichere die Ergebnisreihe in der Variable $row
  $row = $result->fetch_assoc();
  
  // Überprüfe, ob das eingegebene Passwort mit dem in der Datenbank gespeicherten Passwort übereinstimmt
  if (password_verify($passwort, $row['passwort'])) {
    // Wenn das Passwort übereinstimmt, setze die Logged-in-Session-Variable auf true und speichere den Benutzernamen in der Session
    $_SESSION['loggedin'] = true;
    $_SESSION['benutzername'] = $benutzername;
    // Leite den Benutzer zur Startseite weiter
    header("Location: index.php");
  } else {
    // Wenn das Passwort nicht übereinstimmt, gib eine Fehlermeldung aus
    echo "Invalid password.";
  }
} else {
  // Wenn der Benutzer nicht gefunden wurde, gib eine Fehlermeldung aus
  echo "Invalid username.";
}

// Schließe den vorbereiteten Statement und die Verbindung zur Datenbank
$stmt->close();
$conn->close();
?>
