<?php
session_start();

unset($_SESSION['cart']);

if (!isset($_SESSION['order_placed']) || $_SESSION['order_placed'] !== true) {
    header('Location: warenkorb.php');
    exit;
}

unset($_SESSION['order_placed']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellbestätigung</title>
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
        <h1>Vielen Dank für Ihre Bestellung!</h1>
        <p>Ihre Bestellung wurde erfolgreich aufgegeben. Sie erhalten in Kürze eine Bestätigung per E-Mail.</p>
        <p><a href="index.php">Zurück zur Startseite</a></p>
        <p><a href="bestellungen.php">Meine Bestellungen ansehen</a></p>
    </main>

    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>

</body>
</html>
