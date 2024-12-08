<?php
//Nomor 1
session_start();
include "data.php"; 
//Nomor 2
if (!isset($_SESSION['email'])) {
    //Nomor 3
    header("Location: http://localhost/HelloNgalam.com/Login/index.php");
    exit();
}

//Nomor 4
$email_pengguna = $_SESSION['email'];


$query = "SELECT pengguna.ID_Pengguna, pengguna.nama_pengguna, pengguna.no_telp_pengguna
          FROM akun 
          INNER JOIN pengguna ON akun.ID_Akun = pengguna.ID_Akun 
          WHERE akun.email_akun = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email_pengguna);
$stmt->execute();
$result = $stmt->get_result();

$nama_pengguna = "Tidak ditemukan";
$no_telp = "";
$id_pengguna = null;
//Nomor 5
if ($result->num_rows > 0) {
    //Nomor 6
    $row = $result->fetch_assoc();
    $id_pengguna = $row['ID_Pengguna'];
    $nama_pengguna = $row['nama_pengguna'];
    $no_telp = $row['no_telp_pengguna'];
    $_SESSION['id_pengguna'] = $id_pengguna;
    //Nomor 7
} else {
    echo "Akun tidak ditemukan.";
    exit();
}
//Nomor 8
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Nomor 9
    $alamat_pengguna = $_POST['alamat'] ?? '';
    $tanggal_registrasi = date("Y-m-d");

    $query_daur_ulang = "INSERT INTO daur_ulang (ID_Pengguna, alamat_daurUlang, tanggal_registrasi) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query_daur_ulang);
    $stmt->bind_param("iss", $id_pengguna, $alamat_pengguna, $tanggal_registrasi);

    //Nomor 10
    if ($stmt->execute()) {
        //Nomor 11
        $id_daur_ulang = $db->insert_id;

        $jumlah_plastik = $_POST['jumlah_plastik'] ?? 0;
        $jumlah_botol = $_POST['jumlah_botol'] ?? 0;
        $jumlah_kardus = $_POST['jumlah_kardus'] ?? 0;
        $jumlah_kaleng = $_POST['jumlah_kaleng'] ?? 0;
        $jumlah_baju_bekas = $_POST['jumlah_baju_bekas'] ?? 0;
        $jumlah_kain_bekas = $_POST['jumlah_kain_bekas'] ?? 0;

        $query_barang_daur = "INSERT INTO barang_daur (ID_DaurUlang, ID_Pengguna, plastik, botol, kardus, kaleng, baju_bekas, kain_bekas) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query_barang_daur);
        $stmt->bind_param("iiiiiiii", $id_daur_ulang, $id_pengguna, $jumlah_plastik, $jumlah_botol, $jumlah_kardus, $jumlah_kaleng, $jumlah_baju_bekas, $jumlah_kain_bekas);

        //Nomor 12
        if ($stmt->execute()) {
            //Nomor 13
            echo "<script>alert('Data berhasil disimpan!'); window.location.href='http://localhost/HelloNgalam.com/PDU%20-%20Status%20Laporan/index9.php';</script>";
            exit();
        } else {
            //Nomor 14
            echo "<script>alert('Gagal menyimpan data barang: " . $stmt->error . "'); window.history.back();</script>";
        }
    } else {
        //Nomor 15
        echo "<script>alert('Gagal menyimpan data daur ulang: " . $stmt->error . "'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Daur Ulang - Form Registrasi</title>
    <link rel="stylesheet" href="css/styles7.css">
</head>
<body>
    <header id="header">
        <nav>
            <a href="http://localhost/HelloNgalam.com/Program%20Daur%20Ulang/index6.php"><img src="img/🦆 icon _arrow back_.svg" alt=""></a>
        </nav>
        <section>
            <a href=""><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt=""></a>
        </section>   
    </header>

    <main>
        <div class="based-content">
            <div class="top-content">
                <!--Left Image-->
                <form class="form-image">
                    <a href=""><img src="img/Reduce-removebg-preview 1.png" alt=""></a>
                </form>
                <!--Right Form-->
                <form class="form-content" method="POST" action="http://localhost/HelloNgalam.com/PDU-Form%20Registrasi%20copy/index7.php">
                <div class="form-title">
                    <div>Formulir Pendaftaran Program</div>
                    <div>Daur Ulang</div>
                </div>
                <div class="form-input-nama">
                    <input type="text" name="nama" required placeholder=" " value="<?php echo htmlspecialchars($nama_pengguna); ?>" readonly />
                    <label>Nama</label>
                </div>
                <div class="form-input-alamat">
                    <input type="text" name="alamat" required placeholder=" " />
                    <label>Alamat</label>
                </div>
                <div class="form-input-no-telp">
                    <input type="tel" name="no-telp" required placeholder=" " value="<?php echo htmlspecialchars($no_telp); ?>" readonly />
                    <label>No. Telp</label>
                </div>
                <div class="form-input-email">
                    <input type="email" name="email" required placeholder=" " value="<?php echo htmlspecialchars($email_pengguna); ?>" readonly />
                    <label>Email</label>
                </div>
                <div class="form-input-list-barang">
                    <div>
                        <label id="label-plastik">Plastik</label>
                        <input type="number" name="jumlah_plastik" value="0" min="0" />
                        <label id="label-botol">Botol</label>
                        <input type="number" name="jumlah_botol" value="0" min="0" />
                    </div>
                    <div>
                        <label id="label-kardus">Kardus</label>
                        <input type="number" name="jumlah_kardus" value="0" min="0" />
                        <label id="label-kaleng">Kaleng</label>
                        <input type="number" name="jumlah_kaleng" value="0" min="0" />
                    </div>
                    <div>
                        <label id="label-baju-bekas">Baju Bekas</label>
                        <input type="number" name="jumlah_baju_bekas" value="0" min="0" />
                        <label id="label-kain-bekas">Kain Bekas</label>
                        <input type="number" name="jumlah_kain_bekas" value="0" min="0" />
                    </div>
                </div>
                <div>
                    <button class="button-send" type="submit">Send</button>
                </div>
            </form>

            </div>
        </div>
    </main>
</body>
</html>
