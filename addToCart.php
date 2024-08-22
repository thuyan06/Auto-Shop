<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['productId']) && isset($_POST['productName'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];

    // Debugging-Ausgabe
    echo "Produkt erhalten: $productId, $productName\n";

    if (!array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $productName;
        echo "Produkt hinzugefügt";
    } else {
        echo "Produkt bereits im Warenkorb.";
    }
} else {
    echo "Keine Daten erhalten.";
}
?>
