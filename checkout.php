<?php
session_start();
require_once 'vendor/autoload.php'; 

use MongoDB\Client;

$mongoDBConnectionString = 'mongodb://localhost:27017';
$dbName = 'autohaus';
$collectionName = 'bestellungen';

$client = new Client($mongoDBConnectionString);
$db = $client->selectDatabase($dbName);
$collection = $db->selectCollection($collectionName);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kundenID = new MongoDB\BSON\ObjectId($_SESSION['kundenID']);
    $datum = new MongoDB\BSON\UTCDateTime();

    $produkte = [];
    $gesamtpreis = 0;
    foreach ($_SESSION['cart'] as $productId => $productDetails) {
        $produktPreis = fetchProductPriceById($productId);
        $gesamtpreis += $produktPreis * $productDetails['quantity'];

        $produkte[$productId] = [
            'ProduktID' => new MongoDB\BSON\ObjectId($productId),
            'Menge' => $productDetails['quantity'],
            'Preis' => $produktPreis
        ];
    }

    $lieferadresse = [
        'Strasse' => $_POST['strasse'],
        'Stadt' => $_POST['stadt'],
        'PLZ' => $_POST['plz'],
        'Land' => $_POST['land']
    ];

    $bestellung = [
        'KundenID' => $kundenID,
        'Datum' => $datum,
        'Produkte' => $produkte,
        'Gesamtpreis' => $gesamtpreis,
        'Lieferadresse' => $lieferadresse
    ];

    $result = $collection->insertOne($bestellung);

    if ($result->getInsertedCount() === 1) {
        $_SESSION['cart'] = [];
        
        $_SESSION['order_placed'] = true;
        
        header('Location: orderConfirmation.php');
        exit();
    } else {
        echo "Es gab ein Problem beim Speichern der Bestellung.";
    }
    
}

function fetchProductPriceById($productId) {
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
        return $product['Preis']; 
    } else {
        return null; 
    }
}
?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class=header-left>
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
        <div class="header-right">
        <nav>
            <a href="index.php" class="nav-button">Zurück zum Shop</a>
        </nav>
        </div>
    </header>
    
    <main>
        <form method="POST" action="checkout.php">
            <label for="strasse">Strasse:</label>
            <input type="text" id="strasse" name="strasse" required>
            <label for="stadt">Stadt:</label>
            <input type="text" id="stadt" name="stadt" required>
            <label for="plz">PLZ:</label>
            <input type="text" id="plz" name="plz" required>
            <label for="land">Land:</label>
            <input type="text" id="land" name="land" required>

            <button type="submit">Bestellung abschicken</button>
        </form>
    </main>

    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
