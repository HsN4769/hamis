<?php
// Include QR Code library
include 'phpqrcode/qrlib.php';

// Initialize variables
$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
$email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
$amount = isset($_POST['amount']) ? htmlspecialchars(trim($_POST['amount'])) : '';

$message = "";
$qrFile = "";

// Process form data
if ($name && $email && $amount) {
    if (!is_numeric($amount) || $amount <= 0) {
        $message = "Tafadhali ingiza kiasi halali cha malipo.";
    } else {
        // Create image folder if it doesn't exist
        $qrFolder = "image/";
        if (!file_exists($qrFolder)) mkdir($qrFolder, 0777, true);

        // Generate unique QR file
        $qrFile = $qrFolder . uniqid('payment_', true) . ".png";

        // Data to encode in QR
        $qrData = "MALIPO YA ZAWADI - HARUSI YA HAMISI NA SUBIRA\n";
        $qrData .= "Jina: $name\n";
        $qrData .= "Email: $email\n";
        $qrData .= "Kiasi: TSh $amount\n";
        $qrData .= "Tarehe: " . date('Y-m-d H:i:s') . "\n";
        $qrData .= "Maelekezo ya Malipo:\n";
        $qrData .= "Tigo Pesa: 0685103287\n";
        $qrData .= "M-Pesa: 0622273287\n";
        $qrData .= "Airtel Money: 0685103287\n";
        $qrData .= "NMB Bank: 1234567890\n";
        $qrData .= "CRDB Bank: 9876543210\n";
        $qrData .= "Asante kwa mchango wako!";

        // Generate QR code
        QRcode::png($qrData, $qrFile, QR_ECLEVEL_M, 8);

        $message = "Malipo yamekamilika! Hii hapa QR code yako ya uthibitisho:";
    }
} else {
    $message = "Tafadhali jaza taarifa zote ili kuendelea.";
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uthibitisho wa Malipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px;
        }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        .qr-container { background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center; }
        .qr-container img { max-width: 100%; height: auto; }
        a.btn { 
            display: inline-block; 
            margin: 10px; 
            padding: 12px 25px; 
            background: #28a745; 
            color: #fff; 
            text-decoration: none; 
            border-radius: 25px; 
            font-weight: bold; 
            transition: all 0.3s ease;
        }
        a.btn:hover { background: #218838; transform: scale(1.05); }
    </style>
</head>
<body>
    <h2><?php echo $message; ?></h2>

    <?php if ($qrFile && file_exists($qrFile)) { ?>
        <div class="qr-container">
            <img src="<?php echo $qrFile; ?>" alt="QR Code ya Malipo">
        </div>
        <a href="<?php echo $qrFile; ?>" download="QR_Payment_<?php echo date('Y-m-d'); ?>.png" class="btn">📥 Pakua QR Code</a>
        <a href="payment.html" class="btn">🔙 Rudi kwenye ukurasa wa malipo</a>
    <?php } else { ?>
        <a href="payment.html" class="btn">🔙 Rudi nyuma</a>
    <?php } ?>
</body>
</html>
