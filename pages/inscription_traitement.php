<?php
include('../includes/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Cet email est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Insérer l'utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $email, $mot_de_passe);

        if ($stmt->execute()) {
            echo "Inscription réussie ! <a href='connexion.php'>Connectez-vous</a>";
        } else {
            echo "Erreur lors de l'inscription. Veuillez réessayer.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
