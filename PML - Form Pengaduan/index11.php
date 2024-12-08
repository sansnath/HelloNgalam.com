<?php
session_start();
include "data.php"; 

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}
    else {
    header("Location: http://localhost/HelloNgalam.com/Login/index2.php");
    exit();
}

$query = "SELECT ID_Akun FROM akun WHERE email_akun = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$ID_Akun = null;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ID_Akun = $row['ID_Akun'];
}


$ID_Pengguna = null;
if ($ID_Akun !== null)
 {
    $query = "SELECT ID_Pengguna FROM pengguna WHERE ID_Akun = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $ID_Akun); 
    $stmt->execute();
    $result = $stmt->get_result();

        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ID_Pengguna = $row['ID_Pengguna'];
    }
}

$nama_pelapor = "Tidak ditemukan";
if ($ID_Pengguna !== null) {
    $query = "SELECT nama_pengguna FROM pengguna WHERE ID_Pengguna = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $ID_Pengguna);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_pelapor = $row['nama_pengguna'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenisMasalah = htmlspecialchars($_POST['jenis-masalah']);
    $deskripsiMasalah = htmlspecialchars($_POST['deskripsi-masalah']);
    $waktuKejadian = htmlspecialchars($_POST['waktu-kejadian']);
    $buktiPendukung = htmlspecialchars($_POST['bukti-pendukung']);
    $lokasiKejadian = htmlspecialchars($_POST['lokasi-kejadian']);

    if (strpos($buktiPendukung, 'https://drive.google.com/file/d/') === 0 && strpos($buktiPendukung, '/view') !== false) {
        $buktiPendukung = str_replace('/view', '/preview', $buktiPendukung);
    }

    $query = "INSERT INTO laporan_masalah (ID_Pengguna, Jenis_masalah, Deskripsi_masalah, Waktu_kejadian, Lokasi_kejadian, Bukti_pendukung) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isssss", $ID_Pengguna, $jenisMasalah, $deskripsiMasalah, $waktuKejadian, $lokasiKejadian, $buktiPendukung);
    if ($stmt->execute()) {
        echo "<script>
                alert('Data berhasil disimpan!');
                window.location.href = 'http://localhost/HelloNgalam.com/PML%20-%20Status%20Laporan/index14.php';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menyimpan data ke database.');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PML - Form Pengaduan</title>
    <link rel="stylesheet" href="css/styles11.css">
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
                <div class="laporan-title">Segera laporkan masalah lingkungan yang anda temui untuk</div>
                <div class="laporan-title1">kami teruskan ke pihak yang berwenang</div>
            </div>
            <div class="top-content">
                <div class="form-content">
                    <div class="form-title">
                        <div>Formulir Pengaduan</div>
                        <div>Masalah Lingkungan</div>
                    </div>
                    <form action="index11.php" method="POST">
                    <div class="form-input-nama-pelapor">
                    <input type="text" id="nama-pelapor" name="nama-pelapor" value="<?php echo htmlspecialchars($nama_pelapor); ?>" readonly>
                    </div>
                    <div class="form-input-jenis-masalah">
                        <input type="text" name="jenis-masalah" required placeholder=" ">
                        <label>Jenis Masalah</label>
                    </div>
                    <div class="form-input-deskripsi-masalah">
                        <input type="text" name="deskripsi-masalah" required placeholder=" ">
                        <label>Deskripsi Detail Masalah</label>
                    </div>
                    <div class="form-input-bukti-pendukung">
                        <input type="url" name="bukti-pendukung" required placeholder=" ">
                        <label>Upload Bukti Pendukung (Dalam link drive)</label>
                        <a href=""><img src="img/logo upload 1.png" alt=""></a>
                    </div>
                    <div class="form-input-jenis-masalah">
                        <input type="text" name="lokasi-kejadian" required placeholder=" ">
                        <label>Lokasi Kejadian</label>
                    </div>
                    <div class="form-input-waktu-kejadian">
                        <input type="text" name="waktu-kejadian" required placeholder=" ">
                        <label>Waktu Kejadian (Tahun-Bulan-Hari)</label>
                    </div>
                    <div>
                        <button class="button-send">Send</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="superior-content">
                <a href=""><img src="img/Loudspeakers_Clipart_Transparent_PNG_Hd__Loudspeaker_Or_Megaphone_Icon_Symbol_Isolated_On_White_Background__White_Icons__Megaphone_Icons__Background_Icons_PNG_Image_For_Free_Download-5.png" alt=""></a>
            </div>
        </div>
    </main>
</body>
</html>
