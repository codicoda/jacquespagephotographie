<?php require '../block/header.php'; ?>
<meta name="robots" content="noindex, nofollow">
<?php
$galerie_password = $_ENV['GALERIE_PASSWORD'] ?? $_SERVER['GALERIE_PASSWORD'] ?? getenv('GALERIE_PASSWORD') ?? null;
if (!isset($_POST['pass']) || $_POST['pass'] !== $galerie_password) {
?>
<p class="paragraph-priv">Espace privé réservé aux proches détenant le mot de passe.</p>
<form class="private" method="post">
    <span class="mdp">Mot de passe : </span><input class="input" type="password" name="pass">
    <input class="input" type="submit" value="Entrer">
    
</form> 
<?php
    exit;
}
?>
<h1 class="bienvenu">Bienvenue dans l’espace famille !</h1>
<!-- Photos ici -->

<?php require '../block/footer.php'; ?>