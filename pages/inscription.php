<?php
session_start(); // Démarre la session

// Générer un token CSRF s'il n'est pas déjà présent
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Inclusion de la connexion à la base de données
include('../includes/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification du token CSRF
    if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        // Récupération des données du formulaire
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $departement = htmlspecialchars($_POST['departement']);
        $email = htmlspecialchars($_POST['email']);
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, departement, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nom, $prenom, $departement, $email, $mot_de_passe);

            if ($stmt->execute()) {
                $message = "Inscription réussie ! <a href='connexion.php'>Connectez-vous</a>";
            } else {
                // Gestion des erreurs d'insertion
                $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        // Gestion de l'erreur CSRF
        $message = "Erreur CSRF. Veuillez réessayer.";
    }
}

include('../includes/header.php');
?>

<div class="container">
    <h1>Inscription</h1>

    <!-- Affichage du message de statut -->
    <?php if (isset($message)) : ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form method="POST">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="departement">Département :</label>
        <input type="text" id="departement" name="departement" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <!-- Token CSRF -->
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <button type="submit">S'inscrire</button>
    </form>
</div>
</body>
</html>
