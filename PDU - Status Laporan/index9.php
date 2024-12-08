<?php
session_start();
include "data.php";

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $query = "SELECT pengguna.ID_Pengguna 
              FROM pengguna 
              JOIN akun ON pengguna.ID_Akun = akun.ID_Akun 
              WHERE akun.email_akun = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ID_Pengguna = $row['ID_Pengguna'];
    } else {
        echo "<script>alert('Pengguna tidak ditemukan! Silakan login ulang.'); window.location.href='http://localhost/HelloNgalam.com/Login/index2.php';</script>";
        exit();
    }
} else {
    header("Location: http://localhost/HelloNgalam.com/Login/index2.php");
    exit();
}

$query = "SELECT 
            daur_ulang.ID_DaurUlang AS id_daur_ulang, 
            daur_ulang.alamat_daurUlang AS alamat_daur_ulang,
            daur_ulang.tanggal_registrasi AS tanggal_registrasi,
            pengguna.nama_pengguna AS nama_pengguna,
            daur_ulang.status AS status,
            pengguna.nama_pengguna,  
            pengguna.ID_Pengguna AS id_pengguna_laporan
          FROM daur_ulang
          JOIN pengguna ON daur_ulang.ID_Pengguna = pengguna.ID_Pengguna
          WHERE pengguna.ID_Pengguna = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $ID_Pengguna);
$stmt->execute();
$result = $stmt->get_result();

$laporan = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $laporan[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_laporan_id'])) {
    $laporanId = $_POST['delete_laporan_id'];
    $deleteQuery2 = "DELETE FROM barang_daur WHERE ID_DaurUlang = ?";
    $stmt2 = $db->prepare($deleteQuery2);
    $stmt2->bind_param("i", $laporanId);
    $stmt2->execute();
    $stmt2->close();


    $deleteQuery = "DELETE FROM daur_ulang WHERE ID_DaurUlang = ?";
    $stmt1 = $db->prepare($deleteQuery);
    $stmt1->bind_param("i", $laporanId);
    $stmt1->execute();
    $stmt1->close();
    if ($stmt->execute()) {
        echo "<script>alert('Laporan berhasil dihapus!'); window.location.href='http://localhost/HelloNgalam.com/PDU%20-%20Status%20Laporan/index9.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus laporan!'); window.location.href='http://localhost/HelloNgalam.com/PDU%20-%20Status%20Laporan/index9.php';</script>";
    }
    $stmt->close();
    exit();
}

function getStatusColor($status) {
    switch ($status) {
        case 0:
            return "#FFC107"; 
        case 1:
            return "#6C757D";
        case 2:
            return "#28A745";
        case 3:
            return "#28A745";
        default:
            return "#6C757D";
    }
}

function getStatusText($status) {
    switch ($status) {
        case 0:
            return "Not Yet";
        case 1:
            return "Not Yet";
        case 2:
            return "Proceed";
        case 3:
            return "Done";
        default:
            return "Unknown";
    }
}

function getProgressDashArray($status) {
    $dashArrayMapping = [
        0 => "0",
        1 => "0",
        2 => "210 210",
        3 => "415 0",
    ];

    return $dashArrayMapping[$status] ?? "0 420";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daur Ulang</title>
    <link rel="stylesheet" href="css/styles9.css">
</head>
<body>
    <header id="header">
        <nav>
            <a href="http://localhost/HelloNgalam.com/Program%20Daur%20Ulang/index6.php"><img src="img/🦆 icon _arrow back_.svg" alt=""></a>
        </nav>
        <section>
            <a href="#"><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt=""></a>
        </section>   
    </header>

    <main>
        <div class="based-content">
            <div>
                <div class="based-title">Laporan Daur Ulang</div>
            </div>
            <div class="top-content">
                <div class="form-content">
                <?php if (count($laporan) > 3): ?>
                <div class="scrollable-container">
            <?php endif; ?>
            <?php foreach ($laporan as $item): ?>
    <div class="inside-content1">
        <div class="content1-img">
            <a href="#"><img src="img/Profile.png" alt=""></a>
        </div>
        <div class="content1-main">
            <div class="content1-title">Laporan ID: <?= htmlspecialchars($item['id_daur_ulang']) ?></div>
            <div class="content1-article"> 
                <div>Nama Pengguna: <?= htmlspecialchars($item['nama_pengguna']) ?></div>
                <div>Alamat: <?= htmlspecialchars($item['alamat_daur_ulang']) ?></div>
                <div>Tanggal Registrasi: <?= htmlspecialchars($item['tanggal_registrasi']) ?></div>
            </div>
        </div>
        <div class="status1">
            <?php 
                $status = $item['status']; 
                $dashArray = getProgressDashArray($status); 
                $color = getStatusColor($status);
                $text = getStatusText($status);
            ?>
            <svg width="80" height="80" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="40" stroke="#e0e0e0" stroke-width="10" fill="none"></circle>
                <circle 
                    cx="50" cy="50" r="40" 
                    stroke="<?= htmlspecialchars($color) ?>" 
                    stroke-width="10" 
                    stroke-dasharray="<?= htmlspecialchars($dashArray) ?>" 
                    fill="none" 
                    transform="rotate(-90,50,50)"
                ></circle>
                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="14" fill="#333">
                    <?= htmlspecialchars($text) ?>
                </text>
            </svg>
            <?php 
                if ($item['id_pengguna_laporan'] == $ID_Pengguna && $status == 1): 
            ?>
            <div class="action-buttons">
                    <form method="GET" action="http://localhost/HelloNgalam.com/PDU%20-%20Edit/index18.php">
                    <input type="hidden" name="ID_Laporan" value="<?= htmlspecialchars($item['id_daur_ulang']) ?>" />
                    <button type="submit" class="edit-button">Edit</button>
                </form>

                <form method="POST" action="http://localhost/HelloNgalam.com/PDU%20-%20Status%20Laporan/index9.php" onsubmit="return confirm('Apakah anda ingin menghapus laporan ini?');">
                    <input type="hidden" name="delete_laporan_id" value="<?= htmlspecialchars($item['id_daur_ulang']) ?>" />
                    <button type="submit" class="delete-button">Delete</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

                </div>
            </div>
        </div>
    </main>
</body>
</html>
