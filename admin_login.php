<?php
session_start();

// (nur zu Demonstrationszwecken)
$adminBenutzer = 'admin';
$adminPasswort = 'passwort123'; 

if (isset($_POST['login'])) {
    $benutzer = $_POST['benutzer'];
    $passwort = $_POST['passwort'];

    if ($benutzer === $adminBenutzer && $passwort === $adminPasswort) {
        $_SESSION['admin'] = $benutzer;
        header('Location: admin_bereich.php');
        exit();
    } else {
        echo "Falsche Anmeldedaten!";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class=header-left>
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>    
    </header> 
    <main>
    <form method="post" action="">
        <label for="benutzer">Benutzername:</label><br>
        <input type="text" id="benutzer" name="benutzer" required><br>
        <label for="passwort">Passwort:</label><br>
        <input type="password" id="passwort" name="passwort" required><br><br>
        <button type="submit" name="login">Einloggen</button>
    </form>
    </main>
    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
