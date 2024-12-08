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

    $ID_Pengguna = null;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_laporan_id'])) {
    $laporanId = $_POST['delete_laporan_id'];
    $deleteQuery = "DELETE FROM laporan_masalah WHERE ID_Laporan = ?";
    $stmt = $db->prepare($deleteQuery);
    $stmt->bind_param("i", $laporanId);

    if ($stmt->execute()) {
        echo "<script>alert('Laporan berhasil dihapus!'); window.location.href='http://localhost/HelloNgalam.com/PML%20-%20Status%20Laporan/index14.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus laporan!'); window.location.href='http://localhost/HelloNgalam.com/PML%20-%20Status%20Laporan/index14.php';</script>";
    }
    $stmt->close();
    exit();
}

$query = "SELECT 
            laporan_masalah.Jenis_Masalah AS judul_laporan, 
            laporan_masalah.Deskripsi_Masalah AS deskripsi, 
            laporan_masalah.Waktu_Kejadian AS tanggal, 
            laporan_masalah.Bukti_Pendukung AS gambar, 
            laporan_masalah.Lokasi_Kejadian AS lokasi,
            laporan_masalah.ID_Laporan AS id_laporan, 
            laporan_masalah.status_laporan AS status_laporan,
            pengguna.nama_pengguna,
            pengguna.ID_Pengguna AS id_pengguna_laporan
          FROM laporan_masalah 
          JOIN pengguna ON laporan_masalah.ID_Pengguna = pengguna.ID_Pengguna";

$result = $db->query($query);

$laporan = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $laporan[] = $row;
    }
}

function getStatusColor($status) {
    switch ($status) {
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
        1 => "0",
        2 => "210 210",
        3 => "415 0",
    ];

    return $dashArrayMapping[$status] ?? "0 420";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PML - Status Laporan</title>
    <link rel="stylesheet" href="css/styles14.css">
</head>
<body>
    <header id="header">
        <nav>
            <a href="http://localhost/HelloNgalam.com/PLM_HOME/index6.php"><img src="img/🦆 icon _arrow back_.svg" alt=""></a>
        </nav>
        <section>
            <a href=""><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt=""></a>
        </section>   
    </header>

    <main>
        <div class="based-content">
            <div>
                <div class="based-title">Status Laporan</div>
            </div>
            <div class="top-content">
            <div class="form-content">
            <?php if (count($laporan) > 3): ?>
                <div class="scrollable-container">
            <?php endif; ?>
                <?php foreach ($laporan as $data): ?>
                <div class="inside-content1">
                    <div class="content1-img">
                        <a href="#">
                            <iframe src="<?php echo htmlspecialchars($data['gambar']); ?>" width="100%" height="auto" frameborder="0" style="margin-left: 10px;"></iframe>
                        </a>
                    </div>
                    <div class="content1-main">
                        <div class="content1-title"><?php echo htmlspecialchars($data['judul_laporan']); ?></div>
                        <div class="content1-article">
                            <div>Nama Pelapor: <?php echo htmlspecialchars($data['nama_pengguna']); ?></div>
                            <div>Tanggal Melapor: <?php echo htmlspecialchars($data['tanggal']); ?></div>
                            <div>Lokasi Kejadian: <?php echo htmlspecialchars($data['lokasi']); ?></div>
                        </div>
                    </div>
                    <div class="content1-status">
                    <?php if ($data['id_pengguna_laporan'] == $ID_Pengguna): ?>
                        <?php if ($data['status_laporan'] == 1): ?>
                            <div class="progress-circle small">
                                <svg viewBox="-35 -42 90 235" width="120" height="70"> 
                                    <circle class="background" cx="80" cy="100" r="75" stroke="#6C757D" stroke-width="35" fill="none" />
                                    <circle class="progress" cx="80" cy="100" r="75" stroke="<?php echo getStatusColor($data['status_laporan']); ?>" 
                                    stroke-width="35" fill="none" 
                                    stroke-dasharray="<?php echo getProgressDashArray($data['status_laporan']); ?>" />
                                </svg>
                            </div>
                            <div class="status-text small"><?php echo getStatusText($data['status_laporan']); ?></div>
                            <div class="status-actions">
                                <form method="GET" action="http://localhost/HelloNgalam.com/PML%20-%20Edit%20Form%20Pengaduan/index17.php">
                                    <input type="hidden" name="ID_Laporan" value="<?php echo htmlspecialchars($data['id_laporan']); ?>" />
                                    <button type="submit" class="edit-button">Edit</button>
                                </form>
                                <form method="POST" action="http://localhost/HelloNgalam.com/PML%20-%20Status%20Laporan/index14.php" onsubmit="return confirm('Apakah anda ingin menghapus laporan ini?');">
                                    <input type="hidden" name="delete_laporan_id" value="<?php echo htmlspecialchars($data['id_laporan']); ?>" />
                                    <button type="submit" class="delete-button">Delete</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="progress-circle">
                                <svg viewBox="-35 -18 200 235" width="130" height="104">
                                    <circle class="background" cx="80" cy="100" r="100" stroke="#6C757D" stroke-width="35" fill="none" />
                                    <circle class="progress" cx="80" cy="100" r="100" stroke="<?php echo getStatusColor($data['status_laporan']); ?>" 
                                    stroke-width="35" fill="none" 
                                    stroke-dasharray="<?php echo getProgressDashArray($data['status_laporan']); ?>" />
                                </svg>
                            </div>
                            <div class="status-text"><?php echo getStatusText($data['status_laporan']); ?></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="progress-circle">
                            <svg viewBox="-35 -18 200 235" width="130" height="104">
                                <circle class="background" cx="80" cy="100" r="100" stroke="#6C757D" stroke-width="35" fill="none" />
                                <circle class="progress" cx="80" cy="100" r="100" stroke="<?php echo getStatusColor($data['status_laporan']); ?>" 
                                stroke-width="35" fill="none" 
                                stroke-dasharray="<?php echo getProgressDashArray($data['status_laporan']); ?>" />
                            </svg>
                        </div>
                        <div class="status-text"><?php echo getStatusText($data['status_laporan']); ?></div>
                    <?php endif; ?>
                </div>

                </div>
            <?php endforeach; ?>


                </div>
                <div class="superior-content">
                    <a href=""><img class="superior-content" src="img/Loudspeakers_Clipart_Transparent_PNG_Hd__Loudspeaker_Or_Megaphone_Icon_Symbol_Isolated_On_White_Background__White_Icons__Megaphone_Icons__Background_Icons_PNG_Image_For_Free_Download-removebg-preview 1.png" alt=""></a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
