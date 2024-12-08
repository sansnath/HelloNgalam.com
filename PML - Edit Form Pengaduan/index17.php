<?php
//Nomor 1
session_start();
include "data.php"; 

//Nomor 2
if (!isset($_SESSION['email'])) {
    //Nomor 3
    header("Location: http://localhost/HelloNgalam.com/Login/index2.php");
    exit();
}
//Nomor 4
$email = $_SESSION['email'];

$query = "SELECT ID_Akun FROM akun WHERE email_akun = ?";
$stmt = $db->prepare($query);

//Nomor5
if (!$stmt) {
    //Nomor 6
    die("Query preparation failed: " . $db->error);
}
//Nomor 7
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$ID_Akun = $result->fetch_assoc()['ID_Akun'] ?? null;

//Nomor 8
if (!isset($_GET['ID_Laporan']) || empty($_GET['ID_Laporan'])) {
    //Nomor 9
    echo "<script>alert('ID Laporan tidak ditemukan!'); window.location.href='status_laporan.php';</script>";
    exit();
}
//Nomor 10
$ID_Laporan = $_GET['ID_Laporan'];

$report = [];
$nama_pelapor = "Tidak ditemukan";

//Nomor 11
if ($ID_Laporan) {
    //Nomor 12
    $query = "SELECT * FROM laporan_masalah WHERE ID_Laporan = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $db->error);
    }
    //Nomor 13
    $stmt->bind_param("i", $ID_Laporan);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    //Nomor 14
    if ($report) {
        //Nomor 15
        $query = "SELECT p.nama_pengguna 
                  FROM laporan_masalah l 
                  JOIN pengguna p ON l.ID_Pengguna = p.ID_Pengguna 
                  WHERE l.ID_Laporan = ?";
        $stmt = $db->prepare($query);
        //Nomor 16
        if (!$stmt) {
            //Nomor 17
            die("Query preparation failed: " . $db->error);
        }
        //Nomor 18
        $stmt->bind_param("i", $ID_Laporan);
        $stmt->execute();
        $result = $stmt->get_result();
        $nama_pelapor = $result->fetch_assoc()['nama_pengguna'] ?? "Tidak ditemukan";
    }
}
//Nomor 19
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenisMasalah = htmlspecialchars($_POST['jenis-masalah']);
    $deskripsiMasalah = htmlspecialchars($_POST['deskripsi-masalah']);
    $waktuKejadian = htmlspecialchars($_POST['waktu-kejadian']);
    $buktiPendukung = htmlspecialchars($_POST['bukti-pendukung']); // Tambahkan ini
    $lokasiMasalah = htmlspecialchars($_POST['lokasi-masalah']);

    // Validasi dan ubah URL Google Drive jika diperlukan
    if (strpos($buktiPendukung, 'https://drive.google.com/file/d/') === 0 && strpos($buktiPendukung, '/view') !== false) {
        $buktiPendukung = str_replace('/view', '/preview', $buktiPendukung);
    }

    // Update query ke database
    $query = "UPDATE laporan_masalah 
              SET Jenis_masalah = ?, Deskripsi_masalah = ?, Waktu_kejadian = ?, Bukti_pendukung = ?, Lokasi_kejadian = ?
              WHERE ID_Laporan = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $db->error);
    }
    $stmt->bind_param("sssssi", $jenisMasalah, $deskripsiMasalah, $waktuKejadian, $buktiPendukung, $lokasiMasalah, $ID_Laporan);

    if ($stmt->execute()) {
        header("Location: http://localhost/HelloNgalam.com/PML%20-%20Status%20Laporan/index14.php");
        exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PML - Edit Form Pengaduan</title>
    <link rel="stylesheet" href="css/styles17.css">
</head>
<body>
    <header id="header">
        <nav>
            <a href="http://localhost/HelloNgalam.com/PML%20-%20Status%20Laporan/index14.php">
                <img src="img/🦆 icon _arrow back_.svg" alt="Back">
            </a>
        </nav>
        <section>
            <a href=""><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt="Hello Ngalam"></a>
        </section>   
    </header>

    <main>
        <div class="based-content">
            <div class="top-content">
                <div class="form-content">
                    <div class="form-title">
                        <div>EDIT LAPORAN</div>
                    </div>
                    <form method="POST" action="">
                    <div class="form-input-nama-pelapor">
                        <input type="text" name="nama-pelapor" value="<?php echo htmlspecialchars($nama_pelapor); ?>" readonly>
                        <label>Nama Pelapor</label>
                    </div>
                    <div class="form-input-jenis-masalah">
                        <input type="text" name="jenis-masalah" required placeholder="<?php echo htmlspecialchars($report['Jenis_Masalah'] ?? 'Jenis Masalah'); ?>">
                        <a href=""><img src="img/Edit Button.png" alt=""></a>
                    </div>
                    <div class="form-input-deskripsi-masalah">
                        <input type="text" name="deskripsi-masalah" required placeholder="<?php echo htmlspecialchars($report['Deskripsi_Masalah'] ?? 'Deskripsi Masalah'); ?>">
                        <a href=""><img src="img/Edit Button.png" alt=""></a>
                    </div>
                    <div class="form-input-bukti-pendukung">
                        <input type="url" name="bukti-pendukung" required placeholder="<?php echo htmlspecialchars($report['Bukti_Pendukung'] ?? 'URL Bukti Pendukung'); ?>">
                        <a href=""><img src="img/logo upload 1.png" alt="Upload"></a>
                    </div>
                    <div class="form-input-jenis-masalah">
                        <input type="text" name="lokasi-masalah" required placeholder="<?php echo htmlspecialchars($report['Lokasi_Kejadian'] ?? 'Jenis Masalah'); ?>">
                        <a href=""><img src="img/Edit Button.png" alt=""></a>
                    </div>
                    <div class="form-input-waktu-kejadian">
                        <input type="text" name="waktu-kejadian" required placeholder="<?php echo htmlspecialchars($report['Waktu_Kejadian'] ?? 'Waktu Kejadian (YYYY-MM-DD)'); ?>">
                        <a href=""><img src="img/Edit Button.png" alt=""></a>
                    </div>
                    <div>
                        <button class="button-save" type="submit">Save</button>
                    </div>
                </form>
                </div>
            </div>
            <div class="superior-content">
                <a href=""><img src="img/Edit.png" alt="Edit"></a>
            </div>
        </div>
    </main>
</body>
</html>
