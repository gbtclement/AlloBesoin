<?php include('../includes/header.php'); ?>
<h2>Connexion</h2>
<form action="connexion_traitement.php" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>
    
    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    
    <button type="submit">Se connecter</button>
</form>
<?php include('../includes/footer.php'); ?>
