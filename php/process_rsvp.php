<?php
session_start();

// Database configuration (badilisha kwa DB yako)
$host = "localhost";
$dbname = "rsvp_db";
$user = "root";
$pass = "";

// Initialize flash variables
$_SESSION['errors'] = [];
$_SESSION['success'] = '';
$_SESSION['old'] = [
    'jina' => $_POST['jina'] ?? '',
    'barua' => $_POST['barua'] ?? '',
    'ujumbe' => $_POST['ujumbe'] ?? ''
];

// Sanitize input
$jina = htmlspecialchars(trim($_POST['jina'] ?? ''));
$barua = htmlspecialchars(trim($_POST['barua'] ?? ''));
$ujumbe = htmlspecialchars(trim($_POST['ujumbe'] ?? ''));

// Validation
if (!$jina || strlen($jina) < 3) {
    $_SESSION['errors'][] = "Tafadhali ingiza jina sahihi (angalia minimum 3 chars).";
}
if (!$barua || !filter_var($barua, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errors'][] = "Tafadhali ingiza barua pepe sahihi.";
}

// If there are errors, redirect back
if (!empty($_SESSION['errors'])) {
    header("Location: rsvp.php");
    exit();
}

// Option 1: Store in database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO rsvps (jina, barua, ujumbe, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$jina, $barua, $ujumbe]);

    $_SESSION['success'] = "Umefanikiwa kuthibitisha uwepo wako! Asante 😊";

} catch (PDOException $e) {
    // If DB fails, you can optionally send email instead
    $_SESSION['errors'][] = "Tatizo katika ku-save data kwenye database. Tafadhali jaribu tena.";
    header("Location: rsvp.php");
    exit();
}

/*
// Option 2: Send email (comment DB part above if using this)
$to = "wanandoa@example.com";
$subject = "RSVP - Thibitisha Uwepo Wako";
$message = "Jina: $jina\nEmail: $barua\nUjumbe: $ujumbe";
$headers = "From: no-reply@example.com";

if(mail($to, $subject, $message, $headers)){
    $_SESSION['success'] = "Umefanikiwa kuthibitisha uwepo wako! Asante 😊";
} else {
    $_SESSION['errors'][] = "Tatizo katika kutuma email. Tafadhali jaribu tena.";
}
*/

header("Location: rsvp.php");
exit();
