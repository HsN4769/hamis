<?php
// ⚙️ Taarifa za DB
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "hamis";

// 🔌 Unganisha DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kosa la kuunganisha DB: " . $conn->connect_error);
}

// 🛡️ Hakikisha data imetumwa kupitia POST
if (!isset($_POST['jina'], $_POST['kiasi'])) {
    die("Data haijatumwa ipasavyo.");
}

// 🧼 Safisha data
$jina  = $conn->real_escape_string(trim($_POST['jina']));
$kiasi = intval($_POST['kiasi']);

// ✅ Kagua vigezo
if (empty($jina) || $kiasi < 100 || $kiasi > 500000) {
    die("Tafadhali jaza jina na kiasi kati ya TZS 100 - 500,000.");
}

// 🔐 Tengeneza token ya kipekee
$token = uniqid('hamis_', true);

// 💾 Ingiza data kwenye DB
$sql  = "INSERT INTO malipo (jina, kiasi, token) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $jina, $kiasi, $token);

if ($stmt->execute()) {
    // ✅ Tengeneza QR Code
    require_once __DIR__ . '/phpqrcode/qrlib.php';

    // Folder la kuhifadhia QR
    $folder = __DIR__ . "/image/qrcodes/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $file = $folder . $token . ".png";

    // 🧾 Maandishi ndani ya QR
    $qrText = "Jina: $jina\nKiasi: TZS $kiasi\nToken: $token";
    QRcode::png($qrText, $file, QR_ECLEVEL_L, 6);

    // 🎉 Onyesha ujumbe wa mafanikio
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Asante kwa Mchango</title>
    <style>
      body { font-family: 'Segoe UI', sans-serif; background: #f0fff0; text-align: center; padding: 40px; color: #006400; }
      h2 { font-size: 2rem; margin-bottom: 20px; }
      img { margin-top: 20px; border: 2px solid #006400; border-radius: 8px; }
      a { display: inline-block; margin-top: 30px; background: #228b22; color: white; padding: 12px 24px; border-radius: 30px;
          text-decoration: none; font-weight: bold; box-shadow: 0 6px 15px rgba(34, 139, 34, 0.5); }
      a:hover { background: #006400; transform: scale(1.05); }
    </style></head><body>";

    echo "<h2>Asante sana <strong>$jina</strong> kwa kuchangia <strong>TZS $kiasi</strong>!</h2>";
    echo "<p>Hii hapa QR code yako ya uthibitisho:</p>";
    // tumia relative path ili browser ipate image
    $imgPath = "qrcodes/image/love.png" . $token . ".png";
    echo "<img src='$imgPath' alt='QR Code' width='220'>";
    echo "<br><a>ASANTE</a>";

    echo "</body></html>";

} else {
    echo "Kuna tatizo lililotokea: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

