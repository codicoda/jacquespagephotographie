<?php
// Configuration
$to = "contact@jacquespagephotographie.fr";  // Remplace par ton adresse réelle
$subject = "📩 Nouveau message depuis ton site";
$redirectAfter = "contact";  // Page de redirection après envoi

// Vérification basique des données
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'jacquespagephotographie.fr') === false) {
    die("Accès non autorisé.");
}

$nom = isset($_POST['nom']) ? sanitize($_POST['nom']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
$message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

if (!$nom || !$email || !$message) {
    echo "<p class='message'>❌ Veuillez remplir tous les champs correctement.</p>";
    exit;
}

// Construction du message
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8";

$body = "Nom : $nom\nEmail : $email\n\nMessage :\n$message";

// Envoi
if (mail($to, $subject, $body, $headers)) {
    echo "<p class='message'>✅ Message envoyé avec succès. Merci !</p>";
} else {
    echo "<p class='message'>❌ Une erreur est survenue, veuillez réessayer plus tard.</p>";
}
?>
