<?php include('../includes/header.php'); ?>
<h2>Inscription</h2>
<form action="inscription_traitement.php" method="POST">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>
    
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>
    
    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    
    <button type="submit">S'inscrire</button>
</form>
<?php include('../includes/footer.php'); ?>
