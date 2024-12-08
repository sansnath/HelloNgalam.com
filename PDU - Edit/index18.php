<?php
session_start();
include "data.php"; 


if (!$db) {
    die("Koneksi database tidak tersedia: " . mysqli_connect_error());
}

if (!isset($_SESSION['email'])) {
    header("Location: http://localhost/HelloNgalam.com/Login/index.php");
    exit();
}

$email_pengguna = $_SESSION['email'];

$query = "
    SELECT pengguna.ID_Pengguna, pengguna.nama_pengguna, pengguna.no_telp_pengguna, akun.email_akun,
           daur_ulang.alamat_daurUlang, daur_ulang.tanggal_registrasi,
           barang_daur.plastik, barang_daur.botol, barang_daur.kardus, 
           barang_daur.kaleng, barang_daur.baju_bekas, barang_daur.kain_bekas
    FROM akun
    INNER JOIN pengguna ON akun.ID_Akun = pengguna.ID_Akun
    LEFT JOIN daur_ulang ON pengguna.ID_Pengguna = daur_ulang.ID_Pengguna
    LEFT JOIN barang_daur ON daur_ulang.ID_DaurUlang = barang_daur.ID_DaurUlang
    WHERE akun.email_akun = ?
";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email_pengguna);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_pengguna = $row['ID_Pengguna'];
    $nama_pengguna = $row['nama_pengguna'];
    $no_telp = $row['no_telp_pengguna'];
    $email = $row['email_akun'];
    $alamat = $row['alamat_daurUlang'] ?? '';
    $plastik = $row['plastik'] ?? 0;
    $botol = $row['botol'] ?? 0;
    $kardus = $row['kardus'] ?? 0;
    $kaleng = $row['kaleng'] ?? 0;
    $baju_bekas = $row['baju_bekas'] ?? 0;
    $kain_bekas = $row['kain_bekas'] ?? 0;
} else {
    echo "Data tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat_pengguna = $_POST['alamat'] ?? '';
    $plastik = $_POST['jumlah_plastik'] ?? 0;
    $botol = $_POST['jumlah_botol'] ?? 0;
    $kardus = $_POST['jumlah_kardus'] ?? 0;
    $kaleng = $_POST['jumlah_kaleng'] ?? 0;
    $baju_bekas = $_POST['jumlah_baju_bekas'] ?? 0;
    $kain_bekas = $_POST['jumlah_kain_bekas'] ?? 0;

    $query_update_daur_ulang = "
        UPDATE daur_ulang 
        SET alamat_daurUlang = ?
        WHERE ID_Pengguna = ?
    ";
    $stmt = $db->prepare($query_update_daur_ulang);
    $stmt->bind_param("si", $alamat_pengguna, $id_pengguna);
    $stmt->execute();

    $query_update_barang = "
        UPDATE barang_daur
        SET plastik = ?, botol = ?, kardus = ?, kaleng = ?, baju_bekas = ?, kain_bekas = ?
        WHERE ID_Pengguna = ?
    ";
    $stmt = $db->prepare($query_update_barang);
    $stmt->bind_param("iiiiiii", $plastik, $botol, $kardus, $kaleng, $baju_bekas, $kain_bekas, $id_pengguna);
    if ($stmt->execute()) {
        header("Location: http://localhost/HelloNgalam.com/PDU%20-%20Status%20Laporan/index9.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDU - Edit</title>
    <link rel="stylesheet" href="css/styles18.css">
</head>
<body>
    <header id="header">
        <nav>
            <a href="http://localhost/HelloNgalam.com/PDU%20-%20Status%20Laporan/index9.php"><img src="img/🦆 icon _arrow back_.svg" alt=""></a>
        </nav>
        <section>
            <a href=""><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt=""></a>
        </section>   
    </header>

    <main>
        <div class="based-content">
            <div>
                <div class="based-title">Laporan Daur Ulang</div>
            </div>
            <div class="top-content">
                <form class="form-content" method="POST" action="">
                    <div class="inside-content">
                        <div class="content-img">
                            <a href="#"><img src="img/Profile.png" alt=""></a>
                        </div>
                        <div class="content-title">Program Daur Ulang</div>
                        <div class="content-nama">Nama Pengguna: <?php echo htmlspecialchars($nama_pengguna); ?></div>
                        <div class="content-tgl-regist">Tanggal Registrasi: <?php echo htmlspecialchars($row['tanggal_registrasi']); ?></div>
                        <div>
                            <div class="content-alamat">Alamat:</div>
                            <div class="form-input-alamat">
                                <input type="text" name="alamat" value="<?php echo htmlspecialchars($alamat); ?>" required placeholder=" ">
                                <label><?php echo htmlspecialchars($alamat); ?></label>
                                <a href="#"><img src="img/Edit Button.png" alt="Edit"></a>
                            </div>
                        </div>
                        <div class="form-input-list-barang">
                            <div>
                                <label id="label-plastik">Plastik</label>
                                <input type="number" name="jumlah_plastik" value="<?php echo $plastik; ?>" min="0" id="jumlah-plastik">
                                <label id="label-botol">Botol</label>
                                <input type="number" name="jumlah_botol" value="<?php echo $botol; ?>" min="0" id="jumlah-botol">
                            </div>
                            <div>
                                <label id="label-kardus">Kardus</label>
                                <input type="number" name="jumlah_kardus" value="<?php echo $kardus; ?>" min="0" id="jumlah-kardus">
                                <label id="label-kaleng">Kaleng</label>
                                <input type="number" name="jumlah_kaleng" value="<?php echo $kaleng; ?>" min="0" id="jumlah-kaleng">
                            </div>
                            <div>
                                <label id="label-baju-bekas">Baju Bekas</label>
                                <input type="number" name="jumlah_baju_bekas" value="<?php echo $baju_bekas; ?>" min="0" id="jumlah-baju-bekas">
                                <label id="label-kain-bekas">Kain Bekas</label>
                                <input type="number" name="jumlah_kain_bekas" value="<?php echo $kain_bekas; ?>" min="0" id="jumlah-kain-bekas">
                            </div>
                        </div>
                        <div>
                            <button class="button-save" type="submit">Save</button>
                        </div>
                    </div>
                </form>
                <div class="gambar-recycle">
                    <a href=""><img src="img/Reduce-removebg-preview 1.png"></a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
