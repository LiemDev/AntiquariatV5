<?php
session_start();

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
  // Wenn der Benutzer nicht angemeldet ist, wird er zur Login-Seite weitergeleitet oder eine Fehlermeldung angezeigt
  header("Location: login.php");
  exit;
}

// Datenbankverbindung herstellen
include 'db_config.php';

// Benutzerdaten aus der Datenbank abrufen
$benutzername = $_SESSION['benutzername'];
$sql = "SELECT * FROM benutzer WHERE benutzername = '$benutzername'";
$result = $conn->query($sql);
$userData = $result->fetch_assoc();

// Überprüfen, ob das Formular zum Aktualisieren der Benutzerdaten gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Benutzerdaten aus dem Formular erhalten
  $name = $_POST['name'];
  $vorname = $_POST['vorname'];
  $passwort = $_POST['passwort'];
  $email = $_POST['email'];

  // Erstelle eine leere Array für die zu aktualisierenden Felder
  $updateFields = array();

  // Überprüfen, welche Felder ausgefüllt sind und sie dem Array hinzufügen
  if (!empty($name)) {
    $updateFields[] = "name = '$name'";
  }
  if (!empty($vorname)) {
    $updateFields[] = "vorname = '$vorname'";
  }
  if (!empty($passwort)) {
    // Hier kannst du die Passwort-Hashing-Funktion verwenden, bevor du das Passwort in die Datenbank speicherst
    $hashedPassword = password_hash($passwort, PASSWORD_DEFAULT);
    $updateFields[] = "passwort = '$hashedPassword'";
  }
  if (!empty($email)) {
    $updateFields[] = "email = '$email'";
  }

  // Generiere die SQL-Abfrage basierend auf den aktualisierten Feldern
  $updateQuery = "UPDATE benutzer SET " . implode(", ", $updateFields) . " WHERE benutzername = '$benutzername'";

  // Führe die SQL-Abfrage aus
  $conn->query($updateQuery);

  // Überprüfen, ob die Aktualisierung erfolgreich war
  if ($conn->affected_rows > 0) {
    $_SESSION['success_message'] = "Profil erfolgreich aktualisiert.";
  } else {
    $_SESSION['error_message'] = "Fehler beim Aktualisieren des Profils.";
  }

  // Aktualisierte Benutzerdaten aus der Datenbank abrufen
  $sql = "SELECT * FROM benutzer WHERE benutzername = '$benutzername'";
  $result = $conn->query($sql);
  $userData = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css">
  <title>Profile</title>
</head>
<body>
<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="booklist.php">Booklist</a></li>
    <li><a href="customers.php">Customers</a></li>
    <li><a href="contact.php">Contact</a></li>
    <div class="auth-links">
    <?php
      // Überprüfung, ob der Benutzer angemeldet ist oder nicht
      if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): 
    ?>
      <li><a href="profile.php"><?php echo htmlspecialchars($_SESSION['benutzername']); ?></a></li>
      <li><a href="logout.php">Logout</a></li>
      <li><a href="admin.php">Admin</a></li>
    <?php else: ?>
      <!-- Wenn der Benutzer nicht angemeldet ist, werden Optionen zum Anmelden oder Registrieren angezeigt -->
      <li><a href="signup.php">Sign Up</a></li>
      <li><a href="login.php">Login</a></li>
    <?php endif; ?>
    </div>
  </ul>
</nav>

<div class="profile-container">
  <h1 class="profile-title">My Profile</h1>

  <?php if(isset($userData)): ?>
    <div class="profile-info">
      <form id="profileForm" action="" method="POST">
        <p><strong>Benutzername:</strong> <?php echo htmlspecialchars($userData['benutzername']); ?></p>
        <p><strong>Name:</strong> <input type="text" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" class="profile-input"></p>
        <p><strong>Vorname:</strong> <input type="text" name="vorname" value="<?php echo htmlspecialchars($userData['vorname']); ?>" class="profile-input"></p>
        <p><strong>Email:</strong> <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" class="profile-input"></p>
        <p><strong>Neues Passwort:</strong> <input type="password" name="passwort" class="profile-input"></p>
        <button type="submit" class="profile-button">Speichern</button>
      </form>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
      <script>
        // Erfolgsmeldung als Popup-Fenster anzeigen
        function showPopup(message) {
          var popup = document.createElement('div');
          popup.classList.add('popup');
          popup.innerHTML = message;
          document.body.appendChild(popup);
          setTimeout(function() {
            document.body.removeChild(popup);
          }, 3000); // Popup-Fenster für 3 Sekunden anzeigen
        }

        showPopup("<?php echo $_SESSION['success_message']; ?>");
      </script>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
      <script>
        // Fehlermeldung als Popup-Fenster anzeigen
        function showPopup(message) {
          var popup = document.createElement('div');
          popup.classList.add('popup', 'error');
          popup.innerHTML = message;
          document.body.appendChild(popup);
          setTimeout(function() {
            document.body.removeChild(popup);
          }, 3000); // Popup-Fenster für 3 Sekunden anzeigen
        }

        showPopup("<?php echo $_SESSION['error_message']; ?>");
      </script>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
  <?php endif; ?>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Book Gallery. All rights reserved.</p>
</footer>


</body>
</html>
