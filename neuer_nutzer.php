<?php
session_start();

require 'vendor/autoload.php';

// Verbindung zur MongoDB-Datenbank
$client = new MongoDB\Client("mongodb://localhost:27017");
$kundenCollection = $client->autohaus->kunden;

// Neuen Nutzer hinzufügen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lieferadresse = [
        "Strasse" => $_POST['strasse'],
        "Stadt" => $_POST['stadt'],
        "PLZ" => $_POST['plz'],
        "Land" => $_POST['land']
    ];

    $neuerKunde = [
        "Name" => $_POST['name'],
        "E-Mail" => $_POST['email'],
        "Lieferadresse" => $lieferadresse
    ];

    $kundenCollection->insertOne($neuerKunde);

    // Weiterleitung zur Login-Seite
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neuen Nutzer erfassen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
    </header>  
    <main>
    <form action="neuer_nutzer.php" method="POST">
        <h3>Persönliche Angaben</h3>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email" required><br>
        <h3>Lieferadresse</h3>
        <label for="strasse">Strasse:</label>
        <input type="text" id="strasse" name="strasse" required><br>
        <label for="stadt">Stadt:</label>
        <input type="text" id="stadt" name="stadt" required><br>
        <label for="plz">PLZ:</label>
        <input type="text" id="plz" name="plz" required><br>
        <label for="land">Land:</label>
        <input type="text" id="land" name="land" required><br>
        <input type="submit" value="Nutzer erfassen">
    </form>
    </main>
    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
