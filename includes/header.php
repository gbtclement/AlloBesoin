<?php
require_once 'config.php'; // Assurez-vous que le chemin est correct
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Projet</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
</head>
<body>
    <header>
        <nav>
            <a href="<?= BASE_URL ?>index.php">Accueil</a>
            <a href="<?= BASE_URL ?>pages/rechercher.php">Rechercher</a>
            <a href="<?= BASE_URL ?>pages/publier_demande.php">Publier une Demande</a>
            <a href="<?= BASE_URL ?>pages/proposer_service.php">Proposer un Service</a>
            <a href="<?= BASE_URL ?>pages/messagerie.php">Messagerie</a>
            <a href="<?= BASE_URL ?>pages/connexion.php">Connexion</a>
            <a href="<?= BASE_URL ?>pages/inscription.php">Inscription</a>
        </nav>
    </header>
