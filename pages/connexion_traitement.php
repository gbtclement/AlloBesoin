<?php
session_start();
include('../includes/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Rechercher l'utilisateur
    $stmt = $conn->prepare("SELECT id, mot_de_passe FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($id && password_verify($mot_de_passe, $hashed_password)) {
        $_SESSION['utilisateur_id'] = $id;
        header("Location: profil.php");
        exit();
    } else {
        echo "Identifiants incorrects.";
    }

    $stmt->close();
    $conn->close();
}
?>
