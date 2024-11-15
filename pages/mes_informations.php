<?php
session_start();
include('../includes/header.php');
include('../includes/database.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les informations de l'utilisateur depuis la base de données
$stmt = $conn->prepare("SELECT nom, prenom, email, departement, date_inscription FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $utilisateur_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Vérifier si le formulaire a été soumis pour mettre à jour les informations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $departement = htmlspecialchars($_POST['departement']);

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $stmt = $conn->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, departement = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nom, $prenom, $email, $departement, $utilisateur_id);

    if ($stmt->execute()) {
        $message = "Informations mises à jour avec succès.";
        // Rafraîchir les informations de l'utilisateur après la mise à jour
        $user['nom'] = $nom;
        $user['prenom'] = $prenom;
        $user['email'] = $email;
        $user['departement'] = $departement;
        header("Location: profil.php");
        exit();
    } else {
        $message = "Erreur lors de la mise à jour des informations. Veuillez réessayer.";
    }
    $stmt->close();
}

$conn->close();
?>

<h1>Mes informations</h1>

<!-- Affichage du message de statut -->
<?php if (isset($message)) : ?>
    <p><?= $message ?></p>
<?php endif; ?>

<!-- Formulaire de mise à jour des informations -->
<form method="POST">
    <div class="form-group row">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
    </div>
    <div class="form-group row">
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
    </div>
    <div class="form-group row">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="form-group row">
        <label for="departement">Département :</label>
        <input type="text" id="departement" name="departement" value="<?= htmlspecialchars($user['departement']) ?>" required>
    </div>
    <div class="form-group row">
        <label for="date_inscription">Date d'inscription :</label>
        <input type="text" id="date_inscription" name="date_inscription" value="<?= htmlspecialchars($user['date_inscription']) ?>" readonly>
    </div>
    <button type="submit">Mettre à jour</button>
</form>

<?php include('../includes/footer.php'); ?>
