<?php
session_start();
include('../includes/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $utilisateur = $result->fetch_assoc();

        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            // Connexion réussie, on définit la session utilisateur
            $_SESSION['utilisateur_id'] = $utilisateur['id'];
            $_SESSION['nom'] = $utilisateur['nom']; // Optionnel, pour afficher le nom de l'utilisateur dans le header

            header("Location: accueil.php"); // Redirige vers la page d'accueil
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun compte trouvé avec cet email.";
    }

    $stmt->close();
    $conn->close();
}
?>

