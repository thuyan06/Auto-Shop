<?php
session_start();
require_once 'vendor/autoload.php';

use MongoDB\Client;

$mongoDBConnectionString = 'mongodb://localhost:27017';
$dbName = 'autohaus';
$collectionName = 'bestellungen';

// Stellen Sie sicher, dass ein Nutzer eingeloggt ist
if (!isset($_SESSION['kundenID'])) {
    die("Zugriff verweigert. Bitte erst einloggen.");
}

$client = new Client($mongoDBConnectionString);
$db = $client->selectDatabase($dbName);
$collection = $db->selectCollection($collectionName);

// Umwandlung der KundenID aus der Session in ein ObjectId
$kundenIdObj = new MongoDB\BSON\ObjectId($_SESSION['kundenID']);

// Bestellungen des eingeloggten Nutzers abrufen
$bestellungenCursor = $collection->find(['KundenID' => $kundenIdObj]);

$bestellungen = iterator_to_array($bestellungenCursor);
function fetchProductNameById($productId) {
    require_once 'vendor/autoload.php';

    $mongoDBConnectionString = 'mongodb://localhost:27017';
    $dbName = 'autohaus';
    $collectionName = 'produkte';

    $client = new MongoDB\Client($mongoDBConnectionString);
    $db = $client->selectDatabase($dbName);
    $collection = $db->selectCollection($collectionName);

    $productIdObj = new MongoDB\BSON\ObjectId($productId); 
    $query = ['_id' => $productIdObj];
    $product = $collection->findOne($query);

    if ($product !== null) {
        return $product['Name']; 
    } else {
        return "Unbekanntes Produkt"; 
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Bestellungen</title>
    <link rel="stylesheet" href="styles.css">
</head>

<style>body {
    font-family: Arial, sans-serif;
}

.bestellungen {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.bestellung {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
}

.bestellung-details {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.produkte ul {
    list-style: none;
    padding: 0;
}

.lieferadresse p {
    margin: 0;
}

h3, h4 {
    margin: 0 0 10px 0;
}
</style>
<body>
    <header>
        <div class=header-left>
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
        <div class="header-right">
        <nav>
            <ul>
                <li><a href="index.php">Startseite</a></li>
                <?php if (isset($_SESSION['kundenID'])): ?>
                    <li><a href="produkte.php">Produkte</a></li>
                    <li><a href="bestellungen.php">Bestellungen</a></li>
                    <li><a href="bewertungen.php">Bewertungen</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        </div>
    </header>
    
    <main>
    <?php if (!empty($bestellungen)): ?>
        <div class="bestellungen">
        <?php foreach ($bestellungen as $bestellung): ?>
            <div class="bestellung">
            <h3>Bestellung vom <?= $bestellung['Datum']->toDateTime()->format('d.m.Y'); ?></h3>

                <div class="bestellung-details">
                    <div class="produkte">
                        <h4>Produkte:</h4>
                        <ul>
                        <?php foreach ($bestellung['Produkte'] as $produkt): ?>
                            <?php $produktName = fetchProductNameById($produkt['ProduktID']); ?>
                                <li><?= $produkt['Menge']; ?>x <?= htmlspecialchars($produktName); ?> (Preis: <?= htmlspecialchars($produkt['Preis']); ?> €)</li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="lieferadresse">
                        <h4>Lieferadresse:</h4>
                        <p><?= $bestellung['Lieferadresse']['Strasse']; ?><br>
                        <?= $bestellung['Lieferadresse']['PLZ']; ?> <?= $bestellung['Lieferadresse']['Stadt']; ?><br>
                        <?= $bestellung['Lieferadresse']['Land']; ?></p>
                    </div>
                </div>
                <p><strong>Gesamtpreis:</strong> <?= $bestellung['Gesamtpreis']; ?> €</p>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Keine Bestellungen gefunden.</p>
    <?php endif; ?>
    </main>

    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
