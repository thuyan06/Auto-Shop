<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startseite - Legendary Motorsports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.php"><img src="logo.jpg" class="logo"  alt="Logo"></a>
        </div>
        <div class="header-right">
        <nav>
            <ul>
                <?php if (isset($_SESSION['kundenID'])): ?>
                    <!-- Links, die nur angezeigt werden, wenn der Benutzer eingeloggt ist -->
                    <li><a href="produkte.php">Produkte</a></li>
                    <li><a href="bestellungen.php">Bestellungen</a></li>
                    <li><a href="bewertungen.php">Bewertungen</a></li>
                    <li><a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['kundenName']) ?>)</a></li>
                <?php else: ?>
                    <!-- Dieser Link wird immer angezeigt -->
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        </div>
    </header>
    
    <main>
        <h2>Willkommen bei Legendary Motorsports</h2>
        <p>Legendary Motorsports ist der führende Anbieter von Luxusautos und Hochleistungsfahrzeugen. Von klassischen Sportwagen bis hin zu massgeschneiderten Supercars bieten wir Ihnen eine exklusive Auswahl an Fahrzeugen, die Ihre Fahrerlebnisse auf ein neues Niveau heben.</p>
        
        <?php if (!isset($_SESSION['kundenID'])): ?>
            <p>Um unsere Produkte anzeigen zu können, müssen Sie sich einloggen. <a href="login.php">Hier einloggen</a>.</p>
        <?php endif; ?>
    </main>


    <footer>
        &copy; 2024 Legendary Motorsports
    </footer>
</body>
</html>
