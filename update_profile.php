// Startet die Session
<?php
session_start();

// Datenbankverbindung importieren
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Benutzername aus der Session holen
  $benutzername = $_SESSION['benutzername'];

  // Benutzerdaten aus dem Formular erhalten
  $name = $_POST['name'];
  $vorname = $_POST['vorname'];
  $email = $_POST['email'];
  $newPassword = $_POST['new_password'];

  // SQL-Abfrage zum Aktualisieren der Benutzerdaten
  $sql = "UPDATE benutzer SET name = ?, vorname = ?, email = ?";

  // Überprüfen, ob ein neues Passwort angegeben wurde
  if (!empty($newPassword)) {
    // Das neue Passwort hashen, bevor es in die Datenbank eingefügt wird
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql .= ", passwort = ?";
  }

  $sql .= " WHERE benutzername = ?";

  $stmt = $conn->prepare($sql);

  if (!empty($newPassword)) {
    $stmt->bind_param("sssss", $name, $vorname, $email, $hashedPassword, $benutzername);
  } else {
    $stmt->bind_param("ssss", $name, $vorname, $email, $benutzername);
  }
// Schliesst das Statement
  $stmt->execute();
  $stmt->close();

  // Erfolgsantwort senden
  $response = array('success' => true);
  echo json_encode($response);
  exit;
}
?>
