<?php
// Include QR Code library
include 'phpqrcode/qrlib.php';

// Pata data kutoka kwenye form (kama ipo)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';

// Kagua kama data zimejazwa
if ($name && $email && $amount) {
    // Path ya kuhifadhi QR code
    $qrFolder = "image/";
    if (!file_exists($qrFolder)) {
        mkdir($qrFolder);
    }

    // Faili jina kwa QR code
    $qrFile = $qrFolder . uniqid() . ".png";

    // Data itakayoingia kwenye QR
    $qrData = "Jina: $name\nEmail: $email\nKiasi: $amount";

    // Tengeneza QR code
    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 6);

    $message = "Malipo yamekamilika! Hii hapa QR code yako ya uthibitisho:";
} else {
    $message = "Tafadhali jaza taarifa zote ili kuendelea.";
    $qrFile = "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="css/image-styles.css">
</head>
<body>
    <h2><?php echo $message; ?></h2>

    <?php if ($qrFile) { ?>
        <div>
            <img src="<?php echo $qrFile; ?>" alt="QR Code" style="width:200px;height:200px;">
        </div>
        <a href="payment.html">Rudi kwenye ukurasa wa malipo</a>
    <?php } else { ?>
        <a href="payment.html">Rudi nyuma</a>
    <?php } ?>
</body>
</html>
