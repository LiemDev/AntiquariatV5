<?php
// Datenbankkonfiguration importieren
include 'db_config.php';

// Variablen initialisieren
$search_term = '';
$result_search = null;

// Prüfen, ob eine Suchanfrage per POST gesendet wurde und der Suchbegriff nicht leer ist
if (isset($_POST['search']) && !empty($_POST['search'])) {
    // Suchbegriff aus POST-Daten abrufen und in Variable speichern
    $search_term = $_POST['search'];

    // SQL-Abfrage erstellen, um Bücher und Kategorien zu suchen, die den Suchbegriff enthalten
    $sql_search = "SELECT * FROM buecher, kategorien WHERE kurztitle LIKE '%$search_term%' OR autor LIKE '%$search_term%'";

    // Suchergebnisse abrufen und in Variable speichern
    $result_search = $conn->query($sql_search);
}
?>

// Startet die Session
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Search Results</title>
</head>
<body>
<nav>
 
 <ul>
   <li><a href="index.php">Home</a></li>
   <li><a href="booklist.php">Booklist</a></li>
   <li><a href="customers.php">Customers</a></li>
   <li><a href="contact.php">Contact</a></li>
   <div class="auth-links">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
     <li><?php echo htmlspecialchars($_SESSION['benutzername']); ?></li>
     <li><a href="logout.php">Logout</a></li>
     <?php else: ?>
     <li><a href="signup.php">Sign Up</a></li>
     <li><a href="login.php">Login</a></li>
     <?php endif; ?>
   </div>
 </ul>
</nav>
  <div class="search-results">
    <h1>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</h1>
    <?php if ($result_search && $result_search->num_rows > 0): ?>
      <table>
        <thead>
        <tr>
          <th>Katalog</th>
          <th>Nummer</th>
          <th>Name</th>
          <th>Autor</th>
          <th>Kategorie</th>
        </tr>
        </thead>
        <tbody>
          <?php while ($book = $result_search->fetch_assoc()): ?>
            <tr>
            <td><?php echo htmlspecialchars($book['katalog']); ?></td>
            <td><?php echo htmlspecialchars($book['nummer']); ?></td>
            <td><?php echo htmlspecialchars($book['kurztitle']); ?></td>
            <td><?php echo htmlspecialchars($book['autor']); ?></td>
            <td><?php echo htmlspecialchars($book['kategorie']); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No results found for "<?php echo htmlspecialchars($search_term); ?>".</p>
    <?php endif; ?>
    <button onclick="history.back()">Back</button>
  </div>
</body>
</html>

<?php $conn->close(); ?>
