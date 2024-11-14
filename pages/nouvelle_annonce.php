<?php
session_start();
include('../includes/header.php');
include('../includes/database.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $lieu = htmlspecialchars($_POST['lieu']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $tarif = $_POST['tarif'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Traitement de l'image téléchargée
    $image_name = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';

        // Récupérer l'extension de l'image
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        // Générer un nom unique pour éviter les conflits de noms de fichiers
        $image_name = uniqid() . '.' . $extension;
        $upload_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            echo "Erreur lors du téléchargement de l'image.";
            exit();
        }
    }


    // Préparer la requête SQL pour insérer l'annonce
    $stmt = $conn->prepare("INSERT INTO annonces (titre, description, lieu, date_debut, date_fin, tarif, image, utilisateur_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdsis", $titre, $description, $lieu, $date_debut, $date_fin, $tarif, $image_name, $utilisateur_id);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Annonce créée avec succès.";
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