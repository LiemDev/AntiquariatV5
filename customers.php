//  Fügt den Inhalt der Datei "db_config.php" in das aktuelle Skript ein.
<?php
include 'db_config.php';

// Bestimme die aktuelle Seite
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Anzahl der Datensätze pro Seite
$limit = 15;

// Bestimme den Offset-Wert für die Abfrage
$offset = ($page - 1) * $limit;

// Bestimme die Sortierung der Kunden
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'kid';
$sort_options = array('kid', 'vorname', 'name', 'geschlecht', 'kunde_seit', 'email');
if (!in_array($sort, $sort_options)) {
    $sort = 'kid';
}

// SQL-Abfrage, um die Kunden-Datensätze zu laden
$sql_customers = "SELECT * FROM kunden ORDER BY $sort ASC LIMIT $limit OFFSET $offset";
$result_customers = $conn->query($sql_customers);

// SQL-Abfrage, um die Gesamtzahl der Kunden-Datensätze zu erhalten
$sql_total = "SELECT COUNT(*) AS total FROM kunden";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_customers = $row_total['total'];

// Bestimme die Gesamtzahl der Seiten, die benötigt werden, um alle Kunden anzuzeigen
$total_pages = ceil($total_customers / $limit);
?>
// Startet die Session
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css?v=2">
  <title>Customer List</title>
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
        // Wenn der Benutzer angemeldet ist, wird sein Benutzername und eine Option zum Ausloggen angezeigt
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
<div class="customerlist">
  <h1>Customer List</h1>
  <table>
    <thead>
      <tr>
        <th><a href="?sort=kid">ID</a></th>
        <th><a href="?sort=name">Name</a></th>
        <th><a href="?sort=vorname">Prename</a></th>
        <th><a href="?sort=geschlecht">Gender</a></th>
        <th><a href="?sort=kunde_seit">Customer Since</a></th>
        <th><a href="?sort=email">Email</a></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($customer = $result_customers->fetch_assoc()): ?>
      <tr>
        <td><?php echo $customer['kid']; ?></td>
        <td><?php echo htmlspecialchars($customer['name']); ?></td>
        <td><?php echo htmlspecialchars($customer['vorname']); ?></td>
        <td><?php echo htmlspecialchars($customer['geschlecht']); ?></td>
        <td><?php echo htmlspecialchars($customer['kunde_seit']); ?></td>
        <td><?php echo htmlspecialchars($customer['email']); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?php echo $page - 1; ?>">&laquo;</a>
    <?php endif; ?>

    <a href="?page=1" class="<?php echo 1 === $page ? 'active' : ''; ?>">1</a>
    <?php
    $start_page = max(2, $page - 2);
    $end_page = min($total_pages - 1, $page + 2);

    for ($i = $start_page; $i <= $end_page; $i++): ?>
      <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
        <?php echo $i; ?>
      </a>
    <?php endfor; ?>

    <a href="?page=<?php echo $total_pages; ?>" class="<?php echo $total_pages === $page ? 'active' : ''; ?>">
      <?php echo $total_pages; ?>
    </a>

    <?php if ($page < $total_pages): ?>
      <a href="?page=<?php echo $page + 1; ?>">&raquo;</a>
    <?php endif; ?>
  </div>
</div>
</body>
</html>

<?php $conn->close(); ?>
