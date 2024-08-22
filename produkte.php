<?php
session_start();

require 'vendor/autoload.php'; // include Composer's autoloader
$client = new MongoDB\Client("mongodb://localhost:27017");
$produkteCollection = $client->autohaus->produkte;
$bewertungenCollection = $client->autohaus->bewertungen; // Zugriff auf die Kollektion 'bewertungen'

// Funktion zum Erstellen der Sternenbewertung
function createStarRating($sterne) {
    $rating = "";
    for ($i = 0; $i < 5; $i++) {
        if ($i < $sterne) {
            $rating .= "<i class='fas fa-star'></i>"; // Vollstern
        } else {
            $rating .= "<i class='far fa-star'></i>"; // Leerstern
        }
    }
    return $rating;
}

function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Warenkorb bei jedem Aufruf initialisieren, um sicherzustellen, dass er existiert
initializeCart();

if (isset($_POST['addToCart']) && isset($_POST['productId'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName']; // Produktname wird auch über das Formular gesendet

    // Initialisiere das Produkt mit der Anzahl 1, falls noch nicht im Warenkorb vorhanden
    if (!array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = ['name' => $productName, 'quantity' => 1];
    } else {
        // Wenn das Produkt bereits vorhanden ist, erhöhe die Anzahl
        $_SESSION['cart'][$productId]['quantity']++;
    }

    header('Location: warenkorb.php');
    exit();
}
// Warenkorb leeren, wenn ausgeloggt wird
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['cart']); // Warenkorb leeren
    unset($_SESSION['kundenID']); // Nutzer ausloggen
    header('Location: login.php'); // Weiterleitung zum Login
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle Produkte</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <style>
.productov {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 20px;
}

.product-image {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 20px;
}



    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
        <div class="header-right">
            <a href="warenkorb.php" class="warenkorb-button"><i class="fas fa-shopping-cart"></i> Warenkorb (<?= count($_SESSION['cart']) ?>)</a>
            <?php if (isset($_SESSION['kundenID'])): ?>
            <a href="?action=logout" class="logout-button">Logout (<?= htmlspecialchars($_SESSION['kundenName']) ?>)</a>

            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
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
    </header>



    <?php
    $result = $produkteCollection->find(); 
    foreach ($result as $entry) {

        echo "<div class='product'>";
        echo "<strong>Name:</strong> ", $entry['Name'], "<br>";
        echo "<strong>Beschreibung:</strong> ", $entry['Beschreibung'], "<br>";
        echo "<strong>Preis:</strong> ", $entry['Preis'], "€<br>";
        echo "<strong>Kategorie:</strong> ", $entry['Kategorie'], "<br>";
    

        foreach ($entry as $key => $value) {
            if (!in_array($key, ['Name', 'Beschreibung', 'Preis', 'Kategorie', '_id'])) {
                echo "<div class='attribute'>";
                echo "<span class='attribute-key'><strong>" . htmlspecialchars($key) . ":</strong></span> ";
                echo "<span class='attribute-value'>" . htmlspecialchars($value) . "</span>";
                echo "</div>";
            }
        }
        

        $bewertungen = $bewertungenCollection->find(['ProduktID' => $entry['_id']]);
        echo "<div class='bewertungen'>";
        foreach ($bewertungen as $bewertung) {
            echo "<div class='bewertung'>";
            echo "<div class='sternebewertung'>", createStarRating($bewertung['Sternebewertung']), "</div>";
            echo "<div class='bewertungstext'>", $bewertung['Text'], "</div>";
            echo "</div>";
        }
        echo "</div>";
        // Formular für "In den Warenkorb" Button
        echo "<form method='POST' action='produkte.php'>";
        echo "<input type='hidden' name='productId' value='" . $entry['_id'] . "'>";
        echo "<input type='hidden' name='productName' value='" . $entry['Name'] . "'>";
        echo "<button type='submit' name='addToCart' class='addtocart'>";
        echo "  <div class='pretext'>";
        echo "    <i class='fas fa-cart-plus'></i> ADD TO CART";
        echo "  </div>";
        echo "  <div class='pretext done'>";
        echo "    <div class='posttext'><i class='fas fa-check'></i> ADDED</div>";
        echo "  </div>";
        echo "</button>";
        echo "</form>";


        $bildPfadJpg = "images/" . (string)$entry['_id'] . ".jpg"; 
        $bildPfadJpeg = "images/" . (string)$entry['_id'] . ".jpeg";
        
        if (file_exists($bildPfadJpg)) {
            echo "<img src='$bildPfadJpg' alt='" . htmlspecialchars($entry['Name']) . "' class='product-image'>";
        } elseif (file_exists($bildPfadJpeg)) {
            echo "<img src='$bildPfadJpeg' alt='" . htmlspecialchars($entry['Name']) . "' class='product-image'>";
        } else {
            echo "<img src='images/standardbild.jpg' alt='Standardbild' class='product-image'>";
        }

        echo "</div><hr>"; // Trennlinie nach jedem Produkt



    }
    ?>

    <br><br><br><br><br>


    <footer style="z-index: 2">
        &copy; 2024 Legendary Motorsports
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
