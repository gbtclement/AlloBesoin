<?php
session_start();
include('../includes/header.php');
include('../includes/database.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

// Vérifie si un ID d'annonce est passé dans l'URL
if (isset($_GET['id'])) {
    $annonce_id = $_GET['id'];

    // Requête pour obtenir les détails de l'annonce
    $stmt = $conn->prepare("SELECT titre, description, lieu, date_debut, date_fin, tarif, image FROM annonces WHERE id = ? AND utilisateur_id = ?");
    $stmt->bind_param("ii", $annonce_id, $_SESSION['utilisateur_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'annonce existe
    if ($result->num_rows > 0) {
        $annonce = $result->fetch_assoc();

        // Traitement du formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
            // Récupérer les données du formulaire
            $titre = htmlspecialchars($_POST['titre']);
            $description = htmlspecialchars($_POST['description']);
            $lieu = htmlspecialchars($_POST['lieu']);
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $tarif = $_POST['tarif'];

            // Traitement de l'image téléchargée (si présente)
            $image_name = $annonce['image']; // Par défaut, on garde l'image actuelle
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/../uploads/';
                $image_name = basename($_FILES['image']['name']);
                $upload_path = $upload_dir . $image_name;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    echo "Erreur lors du téléchargement de l'image.";
                    exit();
                }
            }
            // Préparer la requête SQL pour mettre à jour l'annonce
            $update_stmt = $conn->prepare("UPDATE annonces SET titre = ?, description = ?, lieu = ?, date_debut = ?, date_fin = ?, tarif = ?, image = ? WHERE id = ? AND utilisateur_id = ?");
            $update_stmt->bind_param("ssssssssi", $titre, $description, $lieu, $date_debut, $date_fin, $tarif, $image_name, $annonce_id, $_SESSION['utilisateur_id']);

            // Vérifier et exécuter la requête
            if ($update_stmt->execute()) {
                echo "<p>Annonce mise à jour avec succès.</p>";
                // Optionnellement, rediriger l'utilisateur vers la page de mes annonces ou vers la page d'affichage de l'annonce
                header("Location: mes_annonces.php");
                exit();
            } else {
                echo "<p>Erreur lors de la mise à jour de l'annonce : " . mysqli_error($conn) . "</p>";
            }

            $update_stmt->close();
        }

        // Traitement de la suppression
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
            // Supprimer l'image du serveur (si l'image existe)
            if ($annonce['image'] && file_exists(__DIR__ . '/../uploads/' . $annonce['image'])) {
                unlink(__DIR__ . '/../uploads/' . $annonce['image']);
            }

            // Requête SQL pour supprimer l'annonce
            $delete_stmt = $conn->prepare("DELETE FROM annonces WHERE id = ? AND utilisateur_id = ?");
            $delete_stmt->bind_param("ii", $annonce_id, $_SESSION['utilisateur_id']);

            if ($delete_stmt->execute()) {
                echo "<p>Annonce supprimée avec succès.</p>";
                // Redirection vers la page des annonces après suppression
                header("Location: mes_annonces.php");
                exit();
            } else {
                echo "<p>Erreur lors de la suppression de l'annonce.</p>";
            }

            $delete_stmt->close();
        }

?>
        <h1>Modifier l'annonce</h1>
        <form method="POST" enctype="multipart/form-data">

            <div class="form-group row">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($annonce['titre']); ?>" required>
            </div>
            <div class="form-group row">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($annonce['description']); ?></textarea>
            </div>
            <div class="form-group row">
                <label for="lieu">Lieu :</label>
                <input type="text" id="lieu" name="lieu" value="<?php echo htmlspecialchars($annonce['lieu']); ?>" required>
            </div>
            <div class="date">
                <div class="form-group row">
                    <label for="date_debut">Date de début :</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($annonce['date_debut']); ?>" required>
                </div>
                <div class="form-group row">
                    <label for="date_fin">Date de fin :</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($annonce['date_fin']); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="tarif">Tarif (€) :</label>
                <input type="number" id="tarif" name="tarif" value="<?php echo htmlspecialchars($annonce['tarif']); ?>" required step="0.01">
            </div>
            <div class="form-group row">
                <label for="image">Image :</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" name="modifier">Modifier l'annonce</button>
        </form>

        <h3>Image actuelle :</h3>
        <img src="../uploads/<?php echo htmlspecialchars($annonce['image']); ?>" alt="Image de l'annonce" class="annonce-image">

        <!-- Bouton de suppression -->
        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
            <button type="submit" name="supprimer">Supprimer l'annonce</button>
        </form>
<?php
    } else {
        echo "<p>Annonce non trouvée ou vous n'avez pas l'autorisation de la modifier.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Erreur : Aucune annonce spécifiée.</p>";
}

$conn->close();

include('../includes/footer.php');
?>