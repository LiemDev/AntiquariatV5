<?php
// Die Funktion fetch_random_books.php wird eingebunden, um zufällig ausgewählte Bücher aus der Datenbank abzurufen
include 'fetch_random_books.php';

// Es werden drei zufällig ausgewählte Bücher aus der Datenbank abgerufen und in der Variable $random_books gespeichert
$random_books = fetch_random_books($conn, 3);
?>

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Book Gallery</title>
</head>
<body>
<nav>
  <ul>
    <li><a href="#">Home</a></li>
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

<div class="search-container">
  <form action="search.php" method="POST">
    <input type="text" class="search" name="search" placeholder="Search for books...">
  </form>
</div>
<div class="title_book">
    <h2>BOOKS YOU MAY LIKE:</h2>
</div>
<div class="random-books">
<?php 
// Eine foreach-Schleife wird verwendet, um die zufällig ausgewählten Bücher auf der Seite anzuzeigen
foreach ($random_books as $book): ?>
  <div class="book">
    <h2>Titel:</h2><?php echo htmlspecialchars($book['title']); ?>
    <h3>Author:</h3><?php echo htmlspecialchars($book['autor']); ?>
  </div>
<?php endforeach; ?>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Book Gallery. All rights reserved.</p>
</footer>

<script>
  // Eine Funktion "loadRandomBooks" wird definiert, die die zufällig ausgewählten Bücher lädt und auf der Seite anzeigt
  function loadRandomBooks() {
    const xhr = new XMLHttpRequest(); // Ein XMLHttpRequest-Objekt wird erstellt
    xhr.onreadystatechange = function() { // Eine Funktion wird definiert, um auf den Statuswechsel des XMLHttpRequest-Objekts zu reagieren
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) { // Wenn der Status "fertig" (readyState 4) ist und der Statuscode 200 (OK) ist, wird die Antwort als HTML in das Element mit der Klasse "random-books" eingefügt
        document.querySelector('.random-books').innerHTML = xhr.responseText;
      }
    };
    xhr.open('GET', 'fetch_random_books_2.php', true); // Die URL des PHP-Skripts, das die zufällig ausgewählten Bücher zurückgibt, wird angegeben
    xhr.send(); // Die Anfrage wird gesendet
  }

  // Die Funktion "loadRandomBooks" wird alle 10 Sekunden aufgerufen, um die zufällig ausgewählten Bücher automatisch zu aktualisieren
  setInterval(loadRandomBooks, 10000);
</script>

</body>
</html>
