<?php
session_start(); // Startet eine neue Session oder setzt eine vorhandene fort

// Stellen Sie sicher, dass Sie zuvor 'vendor/autoload.php' eingebunden haben, falls Sie eine Bibliothek verwenden möchten
require 'vendor/autoload.php'; 
$client = new MongoDB\Client("mongodb://localhost:27017");
$kundenCollection = $client->autohaus->kunden;

$kundenEmail = isset($_GET['kundenEmail']) ? $_GET['kundenEmail'] : null;

if ($kundenEmail) {
    // Versuchen, den Kunden in der Datenbank über die E-Mail zu finden
    $kunde = $kundenCollection->findOne(['E-Mail' => $kundenEmail]);

    if ($kunde) {
        // Speichern der Kunden-ID und des Namens in der Session
        $_SESSION['kundenID'] = (string) $kunde['_id'];
        $_SESSION['kundenName'] = $kunde['Name'];
        // Weiterleitung zur Startseite
        header('Location: index.php');
        exit();
    } else {
        echo "Kein Kunde mit der E-Mail $kundenEmail gefunden.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
    </header>  
    <main>
        <form action="login.php" method="get">
            <label for="kundenEmail">E-Mail:</label>
            <input type="email" id="kundenEmail" name="kundenEmail" required>
            <button type="submit">Login</button>
        </form>

        <a href="neuer_nutzer.php">Ich habe kein Login</a>
        <br><br>
        <a href="admin_login.php">Zum Admin-Bereich</a>
    </main>
    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
