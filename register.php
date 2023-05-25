<?php
// Datenbankkonfiguration importieren
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // POST-Daten vom Formular abrufen
  $name = $_POST['name'];
  $vorname = $_POST['vorname'];
  $benutzername = $_POST['benutzername'];
  $passwort = $_POST['passwort'];
  $passwort_wiederholung = $_POST['passwort_wiederholung'];

  // Überprüfen, ob das Passwort den Anforderungen entspricht
  if (strlen($passwort) < 8) {
    $_SESSION['error_message'] = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    header("Location: signup.php");
    exit;
  }

  // Überprüfen, ob das Passwort und die Passwort-Wiederholung übereinstimmen
  if ($passwort !== $passwort_wiederholung) {
    $_SESSION['error_message'] = "Die Passwörter stimmen nicht überein.";
    header("Location: signup.php");
    exit;
  }

  // Das Passwort hashen, bevor es in die Datenbank eingefügt wird
  $hashed_password = password_hash($passwort, PASSWORD_DEFAULT);

  // SQL-Abfrage erstellen, um den neuen Benutzer in die Datenbank einzufügen
  $sql = "INSERT INTO benutzer (name, vorname, benutzername, passwort) VALUES ('$name', '$vorname', '$benutzername', '$hashed_password')";

  // Prüfen, ob die SQL-Abfrage erfolgreich ausgeführt wurde
  if ($conn->query($sql) === TRUE) {
    // Erfolgsmeldung ausgeben und zur Login-Seite weiterleiten
    $_SESSION['success_message'] = "Registrierung erfolgreich. Bitte melden Sie sich an.";
    header("Location: login.php");
    exit;
  } else {
    // Fehlermeldung ausgeben, falls die SQL-Abfrage fehlschlägt
    $_SESSION['error_message'] = "Fehler bei der Registrierung: " . $conn->error;
    header("Location: signup.php");
    exit;
  }
}

// Datenbankverbindung schließen
$conn->close();
?>
