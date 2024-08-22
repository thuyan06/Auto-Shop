<?php
session_start();
require_once 'vendor/autoload.php';

use MongoDB\Client;

$mongoDBConnectionString = 'mongodb://localhost:27017';
$dbName = 'autohaus';
$collectionName = 'bewertungen';

$client = new Client($mongoDBConnectionString);
$db = $client->selectDatabase($dbName);
$collection = $db->selectCollection($collectionName);

$produkteCollection = $db->selectCollection('produkte');
$produkteCursor = $produkteCollection->find([]);
$produkte = iterator_to_array($produkteCursor);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kundenID = new MongoDB\BSON\ObjectId($_SESSION['kundenID']);
    $produktID = new MongoDB\BSON\ObjectId($_POST['produktID']);
    $sternebewertung = (int)$_POST['sternebewertung'];
    $text = $_POST['text'];

    $bewertung = [
        'KundenID' => $kundenID,
        'ProduktID' => $produktID,
        'Sternebewertung' => $sternebewertung,
        'Text' => $text
    ];

    $result = $collection->insertOne($bewertung);

    if ($result->getInsertedCount() === 1) {
        echo "Bewertung erfolgreich gespeichert.";
    } else {
        echo "Es gab ein Problem beim Speichern der Bewertung.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewertung abgeben</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
        <div class="header-right">
        <nav>
            <a href="index.php" class="nav-button">Zurück zum Shop</a>
        </nav>
    </div>
    </header>
    
    <main>
    <form method="POST" action="bewertungen.php">
    <label for="produktID">Produkt wählen:</label>
    <select id="produktID" name="produktID" required>
        <?php foreach ($produkte as $produkt): ?>
            <option value="<?= $produkt['_id']; ?>"><?= $produkt['Name']; ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <label>Sternebewertung:</label>
    <div id="sternebewertung" class="sternebewertung">
    </div>
    <input type="hidden" id="sternebewertungInput" name="sternebewertung">

    <label for="text">Bewertungstext:</label>
    <textarea id="text" name="text" required></textarea>

    <button type="submit">Bewertung abschicken</button>
</form>
    </main>

    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const sterneContainer = document.getElementById('sternebewertung');
    let bewertung = 0;

    for (let i = 1; i <= 5; i++) {
        const stern = document.createElement('span');
        stern.innerHTML = '☆'; // Nicht hervorgehobener Stern
        stern.style.fontSize = '35px'; // Größe des Sterns anpassen
        stern.style.cursor = 'pointer'; // Cursor beim Hovern ändern
        stern.addEventListener('click', function() {
            bewertung = i;
            document.getElementById('sternebewertungInput').value = bewertung;
            updateSterne();
        });
        sterneContainer.appendChild(stern);
    }

    function updateSterne() {
        const sterne = sterneContainer.querySelectorAll('span');
        sterne.forEach((stern, index) => {
            if (index < bewertung) {
                stern.innerHTML = '★'; 
            } else {
                stern.innerHTML = '☆'; 
            }
            stern.style.fontSize = '35px'; // Größe des Sterns anpassen
        });
    }
});
</script>
</body>
</html>
