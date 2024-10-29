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
$stmt = $conn->prepare("SELECT nom, email, date_inscription FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom, $email, $date_inscription);
$stmt->fetch();
$stmt->close();
?>

<h2>Mon Profil</h2>
<p><strong>Nom :</strong> <?= htmlspecialchars($nom) ?></p>
<p><strong>Email :</strong> <?= htmlspecialchars($email) ?></p>
<p><strong>Date d'inscription :</strong> <?= htmlspecialchars($date_inscription) ?></p>

<?php include('../includes/footer.php'); ?>
