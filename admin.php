<?php
session_start();

// Überprüfen, ob der Benutzer als Admin angemeldet ist
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
  // Wenn der Benutzer nicht als Admin angemeldet ist, wird er zur Login-Seite weitergeleitet oder eine Fehlermeldung angezeigt
  header("Location: login.php");
  exit;
}

// Datenbankverbindung herstellen
include 'db_config.php';

// Funktion zum Löschen eines Buchs
function deleteBook($conn, $bookId) {
  // SQL-Abfrage zum Löschen des Buchs
  $sql = "DELETE FROM buecher WHERE nummer = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $bookId);
  $stmt->execute();
  $stmt->close();
}

// Funktion zum Löschen eines Kunden
function deleteCustomer($conn, $customerId) {
  // SQL-Abfrage zum Löschen des Kunden
  $sql = "DELETE FROM kunden WHERE kid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $customerId);
  $stmt->execute();
  $stmt->close();
}

// Funktion zum Löschen eines Benutzers
function deleteUser($conn, $userId) {
  // SQL-Abfrage zum Löschen des Benutzers
  $sql = "DELETE FROM benutzer WHERE benutzername = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $stmt->close();
}

// Funktion zum Hinzufügen eines Buchs
function addBook($conn, $katalog, $nummer, $kurztitle, $kategorie, $autor) {
  // SQL-Abfrage zum Hinzufügen des Buchs
  $sql = "INSERT INTO buecher (katalog, nummer, kurztitle, kategorie, autor) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $katalog, $nummer, $kurztitle, $kategorie, $autor);
  $stmt->execute();
  $stmt->close();
}

// Funktion zum Hinzufügen eines Kunden
function addCustomer($conn, $geburtstag, $vorname, $nachname, $geschlecht, $kunde_seit, $email) {
  // SQL-Abfrage zum Hinzufügen des Kunden
  $sql = "INSERT INTO kunden (geburtstag, vorname, name, geschlecht, kunde_seit, email) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $geburtstag, $vorname, $nachname, $geschlecht, $kunde_seit, $email);
  $stmt->execute();
  $stmt->close();
}

// Funktion zum Hinzufügen eines Benutzers
function addUser($conn, $benutzername, $name, $vorname, $password, $email) {
  // SQL-Abfrage zum Hinzufügen des Benutzers
  $sql = "INSERT INTO benutzer (benutzername, name, vorname, passwort, email) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $benutzername, $name, $vorname, $password, $email);
  $stmt->execute();
  $stmt->close();
}

// Überprüfen, ob das Löschen eines Buchs angefordert wurde
if (isset($_POST['delete_book'])) {
  $bookId = $_POST['book_id'];
  deleteBook($conn, $bookId);
  echo "<script>showPopup('Book deleted successfully.');</script>";
}

// Überprüfen, ob das Löschen eines Kunden angefordert wurde
if (isset($_POST['delete_customer'])) {
  $customerId = $_POST['customer_id'];
  deleteCustomer($conn, $customerId);
  echo "<script>showPopup('Customer deleted successfully.');</script>";
}

// Überprüfen, ob das Löschen eines Benutzers angefordert wurde
if (isset($_POST['delete_user'])) {
  $userId = $_POST['user_id'];
  deleteUser($conn, $userId);
  echo "<script>showPopup('User deleted successfully.');</script>";
}

// Überprüfen, ob das Hinzufügen eines Buchs angefordert wurde
if (isset($_POST['add_book'])) {
  $katalog = $_POST['book_katalog'];
  $nummer = $_POST['book_nummer'];
  $kurztitle = $_POST['book_kurztitle'];
  $kategorie = $_POST['book_kategorie'];
  $autor = $_POST['book_autor'];
  addBook($conn, $katalog, $nummer, $kurztitle, $kategorie, $autor);
  echo "<script>showPopup('Book added successfully.');</script>";
}

// Überprüfen, ob das Hinzufügen eines Kunden angefordert wurde
if (isset($_POST['add_customer'])) {
  $geburtstag = $_POST['customer_geburtstag'];
  $vorname = $_POST['customer_vorname'];
  $nachname = $_POST['customer_nachname'];
  $geschlecht = $_POST['customer_geschlecht'];
  $kunde_seit = $_POST['customer_kunde_seit'];
  $email = $_POST['customer_email'];
  addCustomer($conn, $geburtstag, $vorname, $nachname, $geschlecht, $kunde_seit, $email);
  echo "<script>showPopup('Customer added successfully.');</script>";
}

// Überprüfen, ob das Hinzufügen eines Benutzers angefordert wurde
if (isset($_POST['add_user'])) {
  $benutzername = $_POST['user_benutzername'];
  $name = $_POST['user_name'];
  $vorname = $_POST['user_vorname'];
  $password = password_hash($_POST['user_password'], PASSWORD_DEFAULT); // Passwort hashen
  $email = $_POST['user_email'];
  addUser($conn, $benutzername, $name, $vorname, $password, $email);
  echo "<script>showPopup('User added successfully.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Admin Page</title>
</head>
<body>
<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="booklist.php">Booklist</a></li>
    <li><a href="customers.php">Customers</a></li>
    <li><a href="contact.php">Contact</a></li>
    <div class="auth-links">
    <li><a href="profile.php"><?php echo htmlspecialchars($_SESSION['benutzername']); ?></a></li>
      <li><a href="logout.php">Logout</a></li>
    </div>
  </ul>
</nav>

<div class="admin-panel">
  <h1>Admin Panel</h1>

  <h2>Delete Book</h2>
  <form action="" method="POST">
    <label for="book_id">Book Nummer:</label>
    <input type="number" id="book_id" name="book_id" required>
    <button type="submit" name="delete_book">Delete Book</button>
  </form>

  <h2>Delete Customer</h2>
  <form action="" method="POST">
    <label for="customer_id">Customer ID:</label>
    <input type="number" id="customer_id" name="customer_id" required>
    <button type="submit" name="delete_customer">Delete Customer</button>
  </form>

  <h2>Delete User</h2>
  <form action="" method="POST">
    <label for="user_id">Username:</label>
    <input type="number" id="user_id" name="user_id" required>
    <button type="submit" name="delete_user">Delete User</button>
  </form>

  <h2>Add Book</h2>
  <form action="" method="POST">
    <label for="book_katalog">Katalog:</label>
    <input type="text" id="book_katalog" name="book_katalog" required>

    <label for="book_nummer">Nummer:</label>
    <input type="text" id="book_nummer" name="book_nummer" required>

    <label for="book_kurztitle">Kurztitle:</label>
    <input type="text" id="book_kurztitle" name="book_kurztitle" required>

    <label for="book_kategorie">Kategorie:</label>
    <input type="text" id="book_kategorie" name="book_kategorie" required>

    <label for="book_autor">Autor:</label>
    <input type="text" id="book_autor" name="book_autor" required>

    <button type="submit" name="add_book">Add Book</button>
  </form>

  <h2>Add Customer</h2>
  <form action="" method="POST">
    <label for="customer_geburtstag">Geburtstag:</label>
    <input type="text" id="customer_geburtstag" name="customer_geburtstag" required>

    <label for="customer_vorname">Vorname:</label>
    <input type="text" id="customer_vorname" name="customer_vorname" required>

    <label for="customer_nachname">Nachname:</label>
    <input type="text" id="customer_nachname" name="customer_nachname" required>

    <label for="customer_geschlecht">Geschlecht:</label>
    <input type="text" id="customer_geschlecht" name="customer_geschlecht" required>

    <label for="customer_kunde_seit">Kunde Seit:</label>
    <input type="text" id="customer_kunde_seit" name="customer_kunde_seit" required>

    <label for="customer_email">Email:</label>
    <input type="email" id="customer_email" name="customer_email" required>

    <button type="submit" name="add_customer">Add Customer</button>
  </form>

  <h2>Add User</h2>
  <form action="" method="POST">
    <label for="user_benutzername">Benutzername:</label>
    <input type="text" id="user_benutzername" name="user_benutzername" required>

    <label for="user_name">Name:</label>
    <input type="text" id="user_name" name="user_name" required>

    <label for="user_vorname">Vorname:</label>
    <input type="text" id="user_vorname" name="user_vorname" required>

    <label for="user_password">Password:</label>
    <input type="password" id="user_password" name="user_password" required>

    <label for="user_email">Email:</label>
    <input type="email" id="user_email" name="user_email" required>

    <button type="submit" name="add_user">Add User</button>
  </form>
</div>

<footer>
  <p>&copy; <?php echo date('Y'); ?> Book Gallery. All rights reserved.</p>
</footer>

<script>
  // JavaScript-Funktion zum Schließen des Popup-Fensters
  function closePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'none';
  }

  // JavaScript-Funktion zum Anzeigen des Popup-Fensters mit einer Nachricht
  function showPopup(message) {
    var popup = document.getElementById('popup');
    var popupMessage = document.getElementById('popup-message');
    popup.style.display = 'flex';
    popupMessage.innerText = message;
    setTimeout(function() {
      popup.style.display = 'none';
    }, 3000);
  }
</script>

<!-- Popup-Fenster für Erfolgsmeldungen -->
<div id="popup" class="popup" style="display: none;">
  <div class="popup-content">
    <span id="popup-message"></span>
  </div>
</div>
</body>
</html>
