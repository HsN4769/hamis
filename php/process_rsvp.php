<?php
session_start();

$toEmail = "wanandoa@example.com"; // Badilisha na email yako halisi
$fromEmail = "no-reply@harusi.com";

$jina = trim($_POST['jina'] ?? '');
$barua = trim($_POST['barua'] ?? '');
$ujumbe = trim($_POST['ujumbe'] ?? '');

$errors = [];

if (strlen($jina) < 3) {
    $errors[] = "Jina lazima liwe na angalau herufi 3.";
}

if (!filter_var($barua, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Barua pepe si sahihi.";
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['jina' => $jina, 'barua' => $barua, 'ujumbe' => $ujumbe];
    header("Location: rsvp_form.php");
    exit();
}

$subject = "RSVP - Thibitisha Uwepo kutoka $jina";
$message = "Mpendwa Wanandoa,\n\n";
$message .= "Mtu mmoja amethibitisha uwepo wake kwenye harusi yenu.\n\n";
$message .= "Jina: $jina\n";
$message .= "Barua Pepe: $barua\n";
$message .= "Ujumbe: " . ($ujumbe ?: 'Hakuna ujumbe ulioongezwa.') . "\n\n";
$message .= "Asanteni kwa kuitumia mfumo wa RSVP.\n\n";
$message .= "Kwa heri,\nHarusi App";

$headers = "From: $fromEmail\r\n";
$headers .= "Reply-To: $barua\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($toEmail, $subject, $message, $headers)) {
    $_SESSION['success'] = "Asante $jina, umefanikiwa kuthibitisha uwepo wako.";
} else {
    $_SESSION['errors'] = ["Tatizo limetokea wakati wa kutuma taarifa. Tafadhali jaribu tena baadaye."];
}

header("Location: rsvp_form.php");
exit();
