<?php
session_start();
include('../includes/header.php');
include('../includes/database.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer le département de l'utilisateur connecté
$departement_utilisateur = '';
if (isset($_SESSION['utilisateur_id'])) {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $stmt = $conn->prepare("SELECT departement FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $utilisateur_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $departement_utilisateur = $row['departement'];
    }
    $stmt->close();
}

// Récupérer les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $lieu = htmlspecialchars($_POST['lieu']);
    $departement = htmlspecialchars($_POST['departement']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $tarif = $_POST['tarif'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Traitement de l'image téléchargée
    $image_name = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $extension;
        $upload_path = $upload_dir . $image_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            echo "Erreur lors du téléchargement de l'image.";
            exit();
        }
    }

    // Préparer la requête SQL pour insérer l'annonce
    $stmt = $conn->prepare("INSERT INTO annonces (titre, description, lieu, departement, date_debut, date_fin, tarif, image, utilisateur_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssii", $titre, $description, $lieu, $departement, $date_debut, $date_fin, $tarif, $image_name, $utilisateur_id);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Annonce créée avec succès.";
        header("Location: mes_annonces.php");
        exit();
    } else {
        echo "Erreur lors de la création de l'annonce.";
    }

    $stmt->close();
}

?>

<h1>Nouvelle annonce</h1>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group row">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required>
    </div>
    <div class="form-group row">
        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>
    </div>
    <div class="form-group row">
        <label for="lieu">Lieu :</label>
        <input type="text" id="lieu" name="lieu" required>
    </div>
    <div class="form-group row">
        <label for="departement">Département :</label>
        <input type="text" id="departement" name="departement" value="<?= htmlspecialchars($departement_utilisateur) ?>" required>
    </div>
    <div class="date">
        <div class="form-group row">
            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" required>
        </div>
        <div class="form-group row">
            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="tarif">Tarif (€) :</label>
        <input type="number" id="tarif" name="tarif" required step="0.01">
    </div>
    <div class="form-group row">
        <label for="image">Image :</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>
    <button type="submit">Créer l'annonce</button>
</form>

<?php include('../includes/footer.php'); ?>
