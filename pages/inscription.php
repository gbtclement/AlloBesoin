<?php
session_start(); // Démarre la session

// Générer un token CSRF s'il n'est pas déjà présent
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

include('../includes/header.php');
?>

<div class="container">
    <h1>Inscription</h1>
    <form method="POST" action="inscription_traitement.php">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

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
