<?php
session_start();

// Stellen Sie sicher, dass der Warenkorb existiert
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Produktanzahl um eins verringern
if (isset($_GET['action']) && $_GET['action'] == 'decrease' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    if (array_key_exists($productId, $_SESSION['cart']) && $_SESSION['cart'][$productId]['quantity'] > 1) {
        $_SESSION['cart'][$productId]['quantity']--;
    } else {
        unset($_SESSION['cart'][$productId]); // Produkt entfernen, wenn die Anzahl <= 1
    }
    header('Location: warenkorb.php');
    exit();
}

// Produkt vollständig entfernen
if (isset($_GET['action']) && $_GET['action'] == 'removeAll' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    if (array_key_exists($productId, $_SESSION['cart'])) {
        unset($_SESSION['cart'][$productId]);
    }
    header('Location: warenkorb.php');
    exit();
}

// Überprüfen, ob der Nutzer eingeloggt ist
if (!isset($_SESSION['kundenID'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warenkorb</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
        <div class="header-right">
        <nav>
            <a href="produkte.php" class="nav-button">Weiter einkaufen</a>
            <?php if (!empty($_SESSION['cart'])): ?>
            <a href="checkout.php" class="nav-button">Zur Kasse</a>
            <?php endif; ?>
        </nav>
        </div>
    </header>
    
    <main>
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Ihr Warenkorb ist leer.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $productId => $productDetails): ?>
                    <li>
                        <?= htmlspecialchars($productDetails['name']) ?> (<?= $productDetails['quantity'] ?>)
                        - <a href="?action=decrease&id=<?= $productId ?>">1 Entfernen</a>
                        - <a href="?action=removeAll&id=<?= $productId ?>">Entfernen</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
