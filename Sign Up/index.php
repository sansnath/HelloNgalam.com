<?php
session_start();
include "data.php"; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['register'])) {
    
    $nama = $_POST['nama'];
    $tglLahir = $_POST['tgllahir'];
    $jenisKelamin = $_POST['jeniskelamin'];
    $noTelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    
    $otp = rand(1000, 9999);

    
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['nama'] = $nama;
    $_SESSION['tgllahir'] = $tglLahir;
    $_SESSION['jeniskelamin'] = $jenisKelamin;
    $_SESSION['notelp'] = $noTelp;
    $_SESSION['alamat'] = $alamat;
    $_SESSION['password'] = $password;

    $sql = "SELECT smtp_host, smtp_username, smtp_password, smtp_port FROM smtp_config WHERE id = 1";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $smtp = $result->fetch_assoc();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $smtp['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtp['smtp_username'];
            $mail->Password = $smtp['smtp_password'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $smtp['smtp_port'];

            $mail->setFrom($smtp['smtp_username'], 'Hello Ngalam');
            $mail->addAddress($email, $nama);
            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP untuk Verifikasi Akun Anda';
            $mail->Body = "<p>Halo, $nama!</p><p>Kode OTP Anda adalah <strong>$otp</strong>. Silakan masukkan kode ini untuk verifikasi akun.</p>";

            $mail->send();
            header("Location: http://localhost/HelloNgalam.com/OTP/index1.php");
            exit();
        } catch (Exception $e) {
            echo "Error: Email tidak dapat dikirim. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Konfigurasi SMTP tidak ditemukan di database.";
    }
    $db->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header id="header">
        <nav>
            <a href="http://localhost/HelloNgalam.com/HomePage_Before/"><img src="img/🦆 icon _arrow back_.svg" alt=""></a>
        </nav>
        <section>
            <a href=""><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt=""></a>
        </section>   
    </header>

    <main>
        <div class="based-content">
            <div class="top-content">
                <form action="index.php"  class="form-content" method="post">
                    <div>
                        <div class="sign-title">Sign Up</div>
                    </div>
                    <div class="form-input-nama">
                        <input type="text" name="nama" required>
                        <label>Nama</label>
                    </div>
                    <div class="form-input-tgl-lahir">
                        <input type="text" name="tgllahir" required>
                        <label>Tanggal Lahir (Tahun-Bulan-Hari)</label>
                    </div>
                    <div class="form-input-jenis-kelamin">
                        <input type="text" name="jeniskelamin" required>
                        <label>Jenis Kelamin</label>
                    </div>                    
                    <div class="form-input-no-telp">
                        <input type="tel" name="notelp" required>
                        <label>No. Telp</label>
                    </div>
                    <div class="form-input-alamat">
                        <input type="text" name="alamat" required>
                        <label>Alamat</label>
                    </div>
                    <div class="form-input-email">
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="form-input-password">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <div>
                        <button name="register" type="submit" class="button-sign-up">Sign Up</button>
                    </div>
                    <div>
                        <a href="http://localhost/HelloNgalam.com/Login/index2.php" class="have-account">Already have an account?</a>
                    </div>
                </form>
                <form class="form-image">
                    <a href=""><img src="img/large-alun-alun-tugu-kota-malang-8c27c18ca551dc2bd8c5629b1a2ad7c7 2.png" alt=""></a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>