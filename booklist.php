<?php session_start(); ?>
<?php
// Datenbankverbindung herstellen
include 'db_config.php';

// Anzahl der Bücher pro Seite und aktuelle Seite aus URL-Parameter auslesen
$books_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Offset für SQL-Abfrage berechnen
$offset = ($page - 1) * $books_per_page;

// Sortierung der Bücher aus URL-Parameter auslesen
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'katalog';
$sort_options = array('katalog', 'nummer', 'kurztitle', 'autor', 'kategorie');

// Wenn angegebene Sortierung nicht erlaubt ist, Standard-Sortierung "katalog" verwenden
if (!in_array($sort, $sort_options)) {
  $sort = 'katalog';
}

// Falls nach Kategorie sortiert wird, dann die Sortierung anhand der ID der Kategorie durchführen
if ($sort == 'kategorie') {
  $sort = 'kategorien.id';
}

// Gesamtanzahl an Büchern ermitteln und Seitenzahl berechnen
$sql_total = "SELECT COUNT(*) FROM buecher";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->execute();
$stmt_total->bind_result($total_books);
$stmt_total->fetch();
$stmt_total->close();
$total_pages = ceil($total_books / $books_per_page);

// SQL-Abfrage für die Bücher aufbauen und ausführen
$sql_books = "SELECT buecher.id, buecher.katalog, buecher.nummer, buecher.kurztitle, buecher.autor, kategorien.kategorie 
              FROM buecher 
              INNER JOIN kategorien ON buecher.kategorie = kategorien.id 
              ORDER BY $sort 
              LIMIT ?, ?";
$stmt_books = $conn->prepare($sql_books);
$stmt_books->bind_param("ii", $offset, $books_per_page);
$stmt_books->execute();
$result_books = $stmt_books->get_result();
$stmt_books->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Booklist</title>
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
  <div class="booklist">
    <h1>Booklist</h1>
    <table>
      <thead>
        <tr>
          <th><a href="?sort=katalog">Katalog</a></th>
          <th><a href="?sort=nummer">Nummer</a></th>
          <th><a href="?sort=kurztitle">Kurztitle</a></th>
          <th><a href="?sort=autor">Autor</a></th>
          <th><a href="?sort=kategorie">Kategorie</a></th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($book = $result_books->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($book['katalog']); ?></td>
            <td><?php echo htmlspecialchars($book['nummer']); ?></td>
            <td><?php echo htmlspecialchars($book['kurztitle']); ?></td>
            <td><?php echo htmlspecialchars($book['autor']); ?></td>
            <td><?php echo htmlspecialchars($book['kategorie']); ?></td>
            <td>
              <a class ="button" href="edit_book.php?id=<?php echo $book['id']; ?>">Edit</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <div class="pagination">
      <?php if ($total_pages > 1): ?>
        <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
        <?php if ($page > 1): ?>
          <a href="?page=1" class="page-link">First</a>
          <a href="?page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
        <?php endif; ?>
        <?php if ($page < $total_pages): ?>
          <a href="?page=<?php echo $page + 1; ?>" class="page-link">Next</a>
          <a href="?page=<?php echo $total_pages; ?>" class="page-link">Last</a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
      <script>
        // Erfolgsmeldung als Popup-Fenster anzeigen
        showPopup("<?php echo $_SESSION['success_message']; ?>");
      </script>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
  </div>
</body>
</html>
