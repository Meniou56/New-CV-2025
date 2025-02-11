<?php

// Récupérer et nettoyer les données du formulaire
$lastname  = isset($_POST['lastname']) ? trim(strip_tags($_POST['lastname'])) : '';
$firstname = isset($_POST['firstname']) ? trim(strip_tags($_POST['firstname'])) : '';
$email     = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject   = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message   = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Vérifier que tous les champs obligatoires sont remplis
if (empty($lastname) || empty($firstname) || empty($email) || empty($subject) || empty($message)) {
    die("Tous les champs sont obligatoires.");
}

// Valider l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Adresse email invalide.");
}

// Limiter la longueur de l’objet (max 100 caractères)
$email_subject = substr("Nouveau message du formulaire de contact : " . $subject, 0, 100);

// Limiter la longueur du message (max 2000 caractères)
$message = substr($message, 0, 2000);

// Définir les informations d'envoi
$to = "contact@emmanuelschmitt.com"; // Adresse de réception des messages
$from = "contact@emmanuelschmitt.com"; // Adresse LWS pour éviter le blocage
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

// Appliquer wordwrap() pour éviter les lignes trop longues (70 caractères max)
$email_body = wordwrap($email_body, 70, "\r\n");

// Envoyer l'e-mail et afficher un message de confirmation ou d'erreur
if (mail($to, $email_subject, $email_body, $headers)) {
    echo "Votre message a été envoyé avec succès.";
} else {
    echo "Une erreur s'est produite lors de l'envoi de votre message.";
}

?>