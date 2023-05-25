<?php
// Fügt die Datenbank-Konfigurationsdatei hinzu, um die Verbindung zur Datenbank herzustellen
include 'db_config.php';

// Eine Funktion mit dem Namen fetch_random_books wird definiert. Diese Funktion nimmt eine Verbindung zur Datenbank und eine Anzahl von Büchern entgegen und gibt ein Array mit den ausgewählten Büchern zurück
function fetch_random_books($conn, $count) {
    // Eine SQL-Abfrage wird ausgeführt, um $count zufällige Bücher aus der Tabelle 'buecher' abzurufen
    $stmt = $conn->query("SELECT * FROM buecher ORDER BY RAND() LIMIT $count");

    // Ein leeres Array wird erstellt, um die ausgewählten Bücher zu speichern
    $books = array();

    // Für jedes ausgewählte Buch wird ein Array mit den Spaltennamen und -werten erstellt und diesem Array $books hinzugefügt
    while ($row = $stmt->fetch_assoc()) {
        $books[] = $row;
    }

    // Das Array mit den ausgewählten Büchern wird zurückgegeben
    return $books;
}
?>
