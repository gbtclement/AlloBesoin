<?php 
session_start();
include('../includes/header.php'); 
include('../includes/database.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$utilisateur_id = $_SESSION['utilisateur_id'];

// Requête pour obtenir toutes les annonces de l'utilisateur
$stmt = $conn->prepare("SELECT id, titre, description, lieu, date_debut, date_fin, tarif, image FROM annonces WHERE utilisateur_id = ?");
$stmt->bind_param("i", $utilisateur_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'utilisateur a des annonces
if ($result->num_rows > 0) {
    echo "<h1>Mes annonces</h1>";
    echo "<a class='new_announcement' href='nouvelle_annonce.php'>Créer une annonce</a>";
    echo "<div class='annonce'>";
    // Afficher chaque annonce
    while ($annonce = $result->fetch_assoc()) {
        // Affichage de l'annonce
        
        echo "<a href='modifier_annonce.php?id=" . $annonce['id'] . "'>" . htmlspecialchars($annonce['titre']) . "</a>";

        
    }
    echo "</div>";
} else {
    echo "<p>Aucune annonce trouvée. <a href='nouvelle_annonce.php'>Créez une annonce</a></p>";
}

$stmt->close();
$conn->close();

include('../includes/footer.php'); 
?>


<!--

        echo "<p><strong>Description :</strong> " . nl2br(htmlspecialchars($annonce['description'])) . "</p>";
        echo "<p><strong>Lieu :</strong> " . htmlspecialchars($annonce['lieu']) . "</p>";
        echo "<p><strong>Date de début :</strong> " . htmlspecialchars($annonce['date_debut']) . "</p>";
        echo "<p><strong>Date de fin :</strong> " . htmlspecialchars($annonce['date_fin']) . "</p>";
        echo "<p><strong>Tarif :</strong> " . number_format($annonce['tarif'], 2, ',', ' ') . " €</p>";
        
        // Affichage de l'image ou d'une image par défaut
        $image_path = $annonce['image'] ? "../uploads/" . htmlspecialchars($annonce['image']) : "../uploads/image_par_defaut.jpg";

        echo "<p><img src='" . $image_path . "' alt='Image de l'annonce' class='annonce-image'></p>";

        echo "<hr>";
-->