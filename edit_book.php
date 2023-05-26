//Startet die Session
<?php session_start(); ?>
<?php
// Datenbankverbindung herstellen
include 'db_config.php';

// Überprüfen, ob eine Buch-ID in der URL übergeben wurde
if (!isset($_GET['id'])) {
    header("Location: booklist.php");
    exit;
}

// Liest den Wert des GET-Parameters "id" und weist ihn der Variablen "$bookId" zu.
$bookId = $_GET['id'];

// Überprüfen, ob das Buch existiert
$sql = "SELECT * FROM buecher WHERE id = $bookId";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: booklist.php");
    exit;
}

$bookData = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Buch löschen
        $deleteSql = "DELETE FROM buecher WHERE id = $bookId";
        if ($conn->query($deleteSql) === TRUE) {
            $_SESSION['success_message'] = "Buch erfolgreich gelöscht.";
            header("Location: booklist.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Fehler beim Löschen des Buches: " . $conn->error;
            header("Location: booklist.php");
            exit;
        }
    } else {
        // Buchdaten aktualisieren
        // Buchdaten aus dem Formular erhalten
        $katalog = $_POST['katalog'];
        $nummer = $_POST['nummer'];
        $kurztitle = $_POST['kurztitle'];
        $title = $_POST['title'];
        $kategorie = $_POST['kategorie'];
        $autor = $_POST['autor'];

        // Buchdaten in der Datenbank aktualisieren
        $sql = "UPDATE buecher SET
                katalog = '$katalog',
                nummer = '$nummer',
                kurztitle = '$kurztitle',
                title = '$title',
                kategorie = '$kategorie',
                autor = '$autor'
                WHERE id = $bookId";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Buch erfolgreich aktualisiert.";
            header("Location: booklist.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Fehler beim Aktualisieren des Buches: " . $conn->error;
            header("Location: booklist.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Edit Book</title>
  <script>
    // JavaScript-Funktion, um das Popup-Fenster anzuzeigen
    function showPopup(message) {
      var popup = document.createElement('div');
      popup.classList.add('popup');
      popup.innerHTML = message;
      document.body.appendChild(popup);
      setTimeout(function() {
        document.body.removeChild(popup);
      }, 3000); // Popup-Fenster für 3 Sekunden anzeigen
    }
    
    // JavaScript-Funktion zur Bestätigung des Löschvorgangs
    function confirmDelete() {
      var confirmDelete = confirm("Sind Sie sicher, dass Sie das Buch löschen möchten?");
      if (confirmDelete) {
        // Formular zum Löschen des Buches absenden
        document.getElementById('deleteForm').submit();
      }
    }
  </script>
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
  <div class="edit-book">
    <h1>Edit Book</h1>
    <form id="editForm" action="edit_book.php?id=<?php echo $bookId; ?>" method="POST">
      <input type="hidden" name="bookId" value="<?php echo $bookData['id']; ?>">
      <label for="katalog">Katalog:</label>
      <input type="text" name="katalog" value="<?php echo $bookData['katalog']; ?>"><br>
      <label for="nummer">Nummer:</label>
      <input type="text" name="nummer" value="<?php echo $bookData['nummer']; ?>"><br>
      <label for="kurztitle">Kurztitle:</label>
      <input type="text" name="kurztitle" value="<?php echo $bookData['kurztitle']; ?>"><br>
      <label for="title">Title:</label>
      <input type="text" name="title" value="<?php echo $bookData['title']; ?>"><br>
      <label for="kategorie">Kategorie:</label>
      <input type="text" name="kategorie" value="<?php echo $bookData['kategorie']; ?>"><br>
      <label for="autor">Autor:</label>
      <input type="text" name="autor" value="<?php echo $bookData['autor']; ?>"><br>
      <button type="submit">Save</button>
      <button class="edit-book1" type="button" onclick="confirmDelete()">Delete</button>
      <a href="booklist.php">Back</a>
    </form>
    
    <form id="deleteForm" action="edit_book.php?id=<?php echo $bookId; ?>" method="POST" style="display: none;">
      <input type="hidden" name="bookId" value="<?php echo $bookData['id']; ?>">
      <input type="hidden" name="delete" value="1">
    </form>
  </div>
  
  <?php if (isset($_SESSION['success_message'])): ?>
    <script>
      // Erfolgsmeldung als Popup-Fenster anzeigen
      showPopup("<?php echo $_SESSION['success_message']; ?>");
    </script>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>
  
</body>
</html>
