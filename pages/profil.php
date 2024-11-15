<?php
session_start();
include('../includes/header.php');
include('../includes/database.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['utilisateur_id'];
$stmt = $conn->prepare("SELECT prenom FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($prenom);
$stmt->fetch();
$stmt->close();
?>

<h1>Bonjour <?= htmlspecialchars($prenom) ?></h1>

<div class="onglets">
    <a href="mes_informations.php">Mes informations</a>
    <a href="mes_annonces.php">Mes annonces</a>
    <a href="mes_favoris.php">Mes favoris</a>
    <a href="messagerie.php">Messagerie</a>
</div>

<a class="btn-deconnexion" href="deconnexion.php">Déconnexion</a>

<?php include('../includes/footer.php'); ?>
