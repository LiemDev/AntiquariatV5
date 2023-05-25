<?php
$servername = "localhost"; // Der Servername, auf dem die MySQL-Datenbank gehostet wird
$username = "root"; // Der Benutzername, um sich bei der Datenbank anzumelden
$password = ""; // Das Passwort des Benutzers, um sich bei der Datenbank anzumelden
$dbname = "book"; // Der Name der Datenbank, auf die zugegriffen werden soll

// Eine neue Verbindung zur Datenbank wird erstellt
$conn = new mysqli($servername, $username, $password, $dbname);

// Wenn die Verbindung fehlschlägt, wird eine Fehlermeldung ausgegeben und das Skript beendet
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Die Zeichenkodierung wird auf UTF-8 gesetzt, um sicherzustellen, dass die Daten korrekt dargestellt werden
$conn->set_charset("utf8");
?>
