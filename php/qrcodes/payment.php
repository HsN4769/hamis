<?php
// Start session ikiwa unataka kuongeza messages baadaye
session_start();

// Include QR code library
include 'phpqrcode/qrlib.php';

// Connect to MySQL
$mysqli = new mysqli("localhost","root","","wedding_db");
if($mysqli->connect_error){
    die("DB Connection failed: ".$mysqli->connect_error);
}

// Pata data kutoka fomu
$jina = trim($_POST['jina'] ?? '');
$kiasi = floatval($_POST['kiasi'] ?? 0);

// Validate
if(!$jina || $kiasi <= 0){
    die("Tafadhali jaza jina na kiasi sahihi.");
}

// Tengeneza folder kwa QR code kama haipo
$qrFolder = "image/";
if(!file_exists($qrFolder)) mkdir($qrFolder);

// Faili la QR
$qrFile = $qrFolder . uniqid() . ".png";

// Data ya QR code
$qrData = "Jina: $jina\nKiasi: $kiasi TZS\nTarehe: " . date('Y-m-d H:i:s');

// Generate QR code
QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 6);

// Hifadhi malipo kwenye DB
$stmt = $mysqli->prepare("INSERT INTO payments (jina,kiasi,qr_file) VALUES (?,?,?)");
$stmt->bind_param("sds", $jina, $kiasi, $qrFile);
$stmt->execute();
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Uthibitisho wa Malipo</title>
<style>
body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #fff0f5, #ffe6f0); text-align: center; padding: 50px;}
h2 { color: #d81b60; font-size: 2.5rem; margin-bottom: 25px; }
p { font-size: 1.2rem; margin-bottom: 25px; }
img { width: 250px; height: 250px; margin-bottom: 20px; }
a { display: inline-block; padding: 12px 30px; background:#d81b60; color:white; border-radius:30px; text-decoration:none; font-weight:700; transition:0.3s;}
a:hover{background:#880e4f; transform:scale(1.05);}
</style>
</head>
<body>

<h2>Malipo yamekamilika!</h2>
<p>Hii hapa QR code yako ya uthibitisho:</p>
<img src="<?= htmlspecialchars($qrFile) ?>" alt="QR Code Malipo">
<br>
<a href="payment.html">Rudi kwenye Malipo</a>
</body>
</html>
