<?php
// Vérifie si une session est déjà active avant de démarrer une nouvelle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>AlloBesoin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <li><a href="profil.php">Mon compte</a></li>
                <?php else: ?>
                    <li><a href="connexion.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="content">
