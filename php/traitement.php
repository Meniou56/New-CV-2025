<?php
session_start();

// Vérifier si la requête est en POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès non autorisé.");
}

// Récupérer et nettoyer les données du formulaire
$lastname  = isset($_POST['lastname']) ? htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8') : '';
$firstname = isset($_POST['firstname']) ? htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8') : '';
$email     = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject   = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8') : '';
$message   = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8') : '';
$honeypot  = isset($_POST['honeypot']) ? trim($_POST['honeypot']) : '';

// Vérifier que tous les champs obligatoires sont remplis
if (empty($lastname) || empty($firstname) || empty($email) || empty($subject) || empty($message)) {
    die("Tous les champs sont obligatoires.");
}

// Vérifier si le champ honeypot est rempli (si oui, c'est un bot)
if (!empty($honeypot)) {
    die("Spam détecté.");
}

// Valider l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Adresse email invalide.");
}

// Vérifier l'absence d'injection d'en-tête
if (preg_match('/[\r\n]/', $email)) {
    die("Tentative d'injection détectée.");
}

// Limiter la longueur de l’objet (max 100 caractères)
$email_subject = substr("Nouveau message du formulaire de contact : " . $subject, 0, 100);

// Limiter la longueur du message (max 2000 caractères)
$message = substr($message, 0, 2000);

// Définir les informations d'envoi
$to = "emmanuelschmitt01@gmail.com";
$from = "contact@emmanuelschmitt.com";
$headers  = "From: " . $from . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Construire le corps du message
$email_body  = "Vous avez reçu un nouveau message depuis le formulaire de contact.\n\n";
$email_body .= "Nom      : " . $lastname . "\n";
$email_body .= "Prénom   : " . $firstname . "\n";
$email_body .= "Email    : " . $email . "\n";
$email_body .= "Objet    : " . $subject . "\n";
$email_body .= "Message  :\n" . $message . "\n";

// Appliquer wordwrap() pour éviter les lignes trop longues
$email_body = wordwrap($email_body, 70, "\r\n");

// Envoyer l'e-mail et gérer les erreurs
if (mail($to, $email_subject, $email_body, $headers)) {
    echo "Votre message a été envoyé avec succès.";
} else {
    error_log("Échec d'envoi du mail de " . $email);
    echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
}
?>