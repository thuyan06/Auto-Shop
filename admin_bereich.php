<?php
session_start();

// Zugriffsschutz
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit();
}

require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$produkteCollection = $client->autohaus->produkte;

// Produkt löschen
$produkte = $produkteCollection->find()->toArray();

// Produkt löschen
// Produkt löschen
if (isset($_POST['delete'])) {
    $produktId = $_POST['produktID']; // Geändert zu 'produktID' vom Selektor
    
    // Suchen und Löschen des Produktbildes
    $existingFiles = glob("images/{$produktId}.*"); // Sucht nach allen Dateien, die mit der ProduktID beginnen
    foreach ($existingFiles as $file) {
        if (file_exists($file)) {
            unlink($file); // Löscht die Datei
        }
    }

    // Löschen des Produkts aus der Datenbank
    $produkteCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($produktId)]);
    echo "<script>window.location = window.location.href;</script>"; // Seite neu laden
}


if (isset($_POST['submit'])) {
    // Basisinformationen
    $productData = [
        'Name' => $_POST['name'],
        'Beschreibung' => $_POST['description'],
        'Preis' => (float)$_POST['price'],
        'Kategorie' => $_POST['category']
    ];

    // Zusätzliche Attribute
    if (!empty($_POST['additionalKeys']) && !empty($_POST['additionalValues'])) {
        $keys = $_POST['additionalKeys'];
        $values = $_POST['additionalValues'];

        foreach ($keys as $index => $key) {
            if (!empty($key) && isset($values[$index])) { // Überprüfung, um leere Felder zu vermeiden
                $productData[$key] = $values[$index];
            }
        }
    }


    $result = $produkteCollection->insertOne($productData);
    $insertedId = (string)$result->getInsertedId();

    // Datei-Upload-Logik
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg']; // Erlaubte Dateierweiterungen
        $ext = strtolower(pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowedExtensions)) {
            $newFileName = $insertedId . '.' . $ext;
            move_uploaded_file($_FILES['productImage']['tmp_name'], "images/$newFileName");
        } else {
            echo "<script>alert('Nur JPG-Dateien sind erlaubt.');</script>";
        }
    }
    

    echo "<script>window.location = window.location.href;</script>"; // Seite neu laden
}
// Produkt bearbeiten vorbereiten
if (isset($_GET['edit'])) {
    $produktId = $_GET['edit'];
    $zuBearbeitendesProdukt = $produkteCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($produktId)]);
}

// Produkt aktualisieren
// Produkt aktualisieren
if (isset($_POST['update'])) {
    $produktId = $_POST['produktIDToUpdate'];
    
    // Bereinige die alten zusätzlichen Attribute
    $zuBearbeitendesProdukt = $produkteCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($produktId)]);
    foreach ($zuBearbeitendesProdukt as $key => $value) {
        if (!in_array($key, ['_id', 'Name', 'Beschreibung', 'Preis', 'Kategorie'])) {
            $produkteCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($produktId)],
                ['$unset' => [$key => ""]]
            );
        }
    }

    // Füge neue/aktualisierte zusätzliche Attribute hinzu
    if (!empty($_POST['editAdditionalKeys']) && !empty($_POST['editAdditionalValues'])) {
        $keys = $_POST['editAdditionalKeys'];
        $values = $_POST['editAdditionalValues'];

        foreach ($keys as $index => $key) {
            if (!empty($key) && isset($values[$index])) {
                $produkteCollection->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($produktId)],
                    ['$set' => [$key => $values[$index]]]
                );
            }
        }
    }

    // Aktualisiere die standardmäßigen Attribute
    $updateData = [
        'Name' => $_POST['editName'],
        'Beschreibung' => $_POST['editDescription'],
        'Preis' => (float)$_POST['editPrice'],
        'Kategorie' => $_POST['editCategory'],
    ];

    $produkteCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($produktId)],
        ['$set' => $updateData]
    );

    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg'];
        $ext = strtolower(pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowedExtensions)) {
            // Lösche die existierende Datei, falls vorhanden
            $existingFiles = glob("images/{$produktId}.*"); 
            foreach ($existingFiles as $file) {
                if (file_exists($file)) {
                    unlink($file); // Datei löschen
                }
            }
            
            $newFileName = "{$produktId}.{$ext}";
            move_uploaded_file($_FILES['productImage']['tmp_name'], "images/{$newFileName}");
        } else {
            echo "<script>alert('Nur JPG-Dateien sind erlaubt.');</script>";
        }
    }
    echo "<script>window.location = window.location.href;</script>";
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Bereich</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="header-left">
<a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
    </header>  
</div>
    <main>
    <h2>Produkte löschen</h2>
    <form method="post" action="">
        <label for="produktID">Produkt auswählen:</label><br>
        <select id="produktID" name="produktID" required>
            <?php foreach ($produkte as $produkt): ?>
                <option value="<?= (string) $produkt['_id']; ?>"><?= $produkt['Name']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit" name="delete">Produkt Löschen</button>
    </form>

    <h2>Neues Produkt hinzufügen</h2>
    <form id="productForm" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Beschreibung:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="price">Preis:</label>
        <input type="number" id="price" name="price" required><br>

        <label for="category">Kategorie:</label>
        <input type="text" id="category" name="category" required><br>

        <div id="additionalAttributes">
        <!-- Hier kommen die zusätzlichen Felder -->
        </div>

        <button type="button" onclick="addAttribute()">+ Zusätzliches Attribut</button>
        <br><br>
        <label for="productImage">Produktbild:</label>
        <input type="file" id="productImage" name="productImage" accept=".jpg, .jpeg" required><br>


        <button type="submit" name="submit">Produkt speichern</button>
    </form>



    <h2>Produkt zum bearbeiten wählen:</h2>

    <select id="productSelect" onchange="editProduct()">
    <option value="">Bitte wählen...</option>
    <?php foreach ($produkte as $produkt): ?>
        <option value="<?= $produkt['_id']; ?>"><?= $produkt['Name']; ?></option>
    <?php endforeach; ?>
</select>

<script>
function editProduct() {
    var productId = document.getElementById("productSelect").value;
    if(productId) {
        window.location.href = 'admin_bereich.php?edit=' + productId;
    }
}
</script>

    
    <?php if(isset($zuBearbeitendesProdukt)): ?>
    <h2>Produkt bearbeiten: <?= $zuBearbeitendesProdukt['Name'] ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="produktIDToUpdate" value="<?= (string)$zuBearbeitendesProdukt['_id'] ?>">
        
        <label for="editName">Name:</label>
        <input type="text" id="editName" name="editName" value="<?= $zuBearbeitendesProdukt['Name'] ?>" required><br>

        <label for="editDescription">Beschreibung:</label>
        <textarea id="editDescription" name="editDescription" required><?= $zuBearbeitendesProdukt['Beschreibung'] ?></textarea><br>

        <label for="editPrice">Preis:</label>
        <input type="number" id="editPrice" name="editPrice" value="<?= $zuBearbeitendesProdukt['Preis'] ?>" required><br>

        <label for="editCategory">Kategorie:</label>
        <input type="text" id="editCategory" name="editCategory" value="<?= $zuBearbeitendesProdukt['Kategorie'] ?>" required><br>

        <div id="editAdditionalAttributes">
            <!-- Zusätzliche Attribute werden hier per JavaScript eingefügt -->
        </div>

        <button type="button" onclick="addEditAttribute()">+ Zusätzliches Attribut</button><br><br>

        <label for="editProductImage">Produktbild:</label>
        <input type="file" id="editProductImage" name="productImage" accept=".jpg, .jpeg"><br>

        <button type="submit" name="update">Änderungen speichern</button>
    </form>
<?php endif; ?>


    <br>

    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>



<?php
// Logout-Logik
if (isset($_POST['logout'])) {
    unset($_SESSION['admin']); // Admin-Session beenden
    header('Location: login.php'); // Weiterleitung zur Login-Seite
    exit();
}
?>
</main>
<br><br>
<footer>
        &copy; 2024 Legendary Motorsports
    </footer>

    <script>
function addAttribute() {
    const container = document.getElementById('additionalAttributes');
    const div = document.createElement('div');
    div.className = 'attribute';
    div.innerHTML = `
        <input type="text" name="additionalKeys[]" placeholder="Attribut">
        <input type="text" name="additionalValues[]" placeholder="Wert">
    `;
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.innerText = '-';
    removeButton.addEventListener('click', function() { removeAttribute(removeButton); });
    div.appendChild(removeButton);
    container.appendChild(div);
}

function removeAttribute(button) {
    button.parentElement.remove();
}

function addEditAttribute(key = '', value = '') {
    const container = document.getElementById('editAdditionalAttributes');
    const div = document.createElement('div');
    div.className = 'attribute';
    div.innerHTML = `
        <input type="text" name="editAdditionalKeys[]" value="${key}" placeholder="Attribut">
        <input type="text" name="editAdditionalValues[]" value="${value}" placeholder="Wert">
    `;
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.innerText = '-';
    removeButton.addEventListener('click', function() { removeAttribute(removeButton); });
    div.appendChild(removeButton);
    container.appendChild(div);
}


<?php 
// Füge bestehende zusätzliche Attribute hinzu
if (isset($zuBearbeitendesProdukt)) {
    foreach ($zuBearbeitendesProdukt as $key => $value) {
        if (!in_array($key, ['_id', 'Name', 'Beschreibung', 'Preis', 'Kategorie'])) {
            echo "addEditAttribute('$key', '$value');";
        }
    }
}
?>
</script>

</body>
</html>
