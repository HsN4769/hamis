<?php
// ⚙️ Taarifa za DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hamis";

// 🔌 Unganisha DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kosa la kuunganisha DB: " . $conn->connect_error);
}

// 🛡️ Hakikisha data imetumwa
if (!isset($_POST['jina'], $_POST['kiasi'])) {
    die("Data haijatumwa ipasavyo.");
}

// 🧼 Safisha data
$jina = $conn->real_escape_string(trim($_POST['jina']));
$kiasi = intval($_POST['kiasi']);

// ✅ Kagua vigezo
if (empty($jina) || $kiasi < 100 || $kiasi > 500000) {
    die("Tafadhali jaza jina na kiasi kati ya TZS 100 - 500,000.");
}

// 🔐 Tengeneza token ya kipekee
$token = uniqid('hamis_');

// 💾 Ingiza DB
$sql = "INSERT INTO malipo (jina, kiasi, token) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $jina, $kiasi, $token);

if ($stmt->execute()) {
    // ✅ QR Code
    require_once 'phpqrcode/qrlib.php';

    $folder = "../images/qrcodes/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $file = $folder . $token . ".png";
    QRcode::png($token, $file, QR_ECLEVEL_L, 6);

    // 🖼️ Ongeza logo kwenye QR
    $qr = imagecreatefrompng($file);
    $logoPath = "../images/logo.png";
    if (file_exists($logoPath)) {
        $logo = imagecreatefrompng($logoPath);

        $qr_width = imagesx($qr);
        $qr_height = imagesy($qr);
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);

        $logo_qr_width = $qr_width / 4;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;

        imagecopyresampled($qr, $logo,
            ($qr_width - $logo_qr_width) / 2,
            ($qr_height - $logo_qr_height) / 2,
            0, 0,
            $logo_qr_width, $logo_qr_height,
            $logo_width, $logo_height);

        imagepng($qr, $file);
    }

    // 🎉 Onyesha ujumbe wa mafanikio
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Asante kwa Mchango</title>
    <style>
      body { font-family: 'Segoe UI', sans-serif; background: #fff0f5; text-align: center; padding: 40px; color: #880e4f; }
      h2 { font-size: 2rem; margin-bottom: 20px; }
      img { margin-top: 20px; box-shadow: 0 0 25px #ff1493; border-radius: 12px; }
      a { display: inline-block; margin-top: 30px; background: #d81b60; color: white; padding: 12px 24px; border-radius: 30px;
          text-decoration: none; font-weight: bold; box-shadow: 0 6px 15px rgba(216, 27, 96, 0.5); }
      a:hover { background: #880e4f; transform: scale(1.05); }
    </style></head><body>";

    echo "<h2>Asante sana <strong>$jina</strong> kwa kuchangia <strong>TZS $kiasi</strong>!</h2>";
    echo "<p>Hii hapa QR code yako ya uthibitisho:</p>";
    echo "<img src='$file' alt='QR Code' width='220'>";
    echo "<br><a href='../payment.html'>Rudi kwenye ukurasa wa malipo</a>";

    echo "</body></html>";

} else {
    echo "Kuna tatizo lililotokea: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
