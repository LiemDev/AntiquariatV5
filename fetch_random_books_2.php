<?php
// Die Funktion fetch_random_books.php wird eingebunden, um zufällig ausgewählte Bücher aus der Datenbank abzurufen
include 'fetch_random_books.php';

// Es werden drei zufällig ausgewählte Bücher aus der Datenbank abgerufen und in der Variable $random_books gespeichert
$random_books = fetch_random_books($conn, 3);
?>

<?php foreach ($random_books as $book): ?>
    <!-- Für jedes der drei ausgewählten Bücher wird ein neues Div-Element mit der Klasse "book" erstellt -->
    <div class="book">
        <!-- Der Titel des Buches wird in einem H2-Element angezeigt -->
        <h2>Titel:</h2><?php echo htmlspecialchars($book['title']); ?>
        <!-- Der Autor des Buches wird in einem H3-Element angezeigt -->
        <h3>Author:</h3><?php echo htmlspecialchars($book['autor']); ?>
    </div>
<?php endforeach; ?>
