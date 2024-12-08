<?php
session_start();
include "data.php";

if (isset($_POST['verify'])) {
    $otpInput = $_POST['number-1'] . $_POST['number-2'] . $_POST['number-3'] . $_POST['number-4'];

    var_dump($_SESSION);

    if ($otpInput == $_SESSION['otp']) {
        if (isset($_SESSION['email'], $_SESSION['password'], $_SESSION['nama'], $_SESSION['tgllahir'], $_SESSION['jeniskelamin'], $_SESSION['notelp'], $_SESSION['alamat'])) {
            $email = $_SESSION['email'];
            $password = $_SESSION['password'];
            $nama = $_SESSION['nama'];
            $tglLahir = $_SESSION['tgllahir'];
            $jenisKelamin = $_SESSION['jeniskelamin'];
            $noTelp = $_SESSION['notelp'];
            $alamat = $_SESSION['alamat'];

            
            $check_email = "SELECT * FROM akun WHERE email_akun = '$email'";
            $result = mysqli_query($db, $check_email);
            if (mysqli_num_rows($result) > 0) {
                echo "Email sudah terdaftar. Silakan gunakan email lain.";
            } else {
                
                $sql_akun = "INSERT INTO akun (email_akun, password_akun) VALUES ('$email', '$password')";
                if (mysqli_query($db, $sql_akun)) {
                    $idAkun = mysqli_insert_id($db);

                    $sql_pengguna = "INSERT INTO pengguna (nama_pengguna, tgl_lahir_pengguna, jenis_kelamin_pengguna, no_telp_pengguna, alamat_pengguna, ID_Akun)
                                     VALUES ('$nama', '$tglLahir', '$jenisKelamin', '$noTelp', '$alamat', '$idAkun')";

                    if (mysqli_query($db, $sql_pengguna)) {
                        echo "Registrasi berhasil!";
                        header("Location: http://localhost/HelloNgalam.com/Login/index2.php");
                        exit();
                    } else {
                        echo "Error: " . mysqli_error($db);
                    }
                } else {
                    echo "Error: " . mysqli_error($db);
                }
            }
        } else {
            echo "Data session tidak lengkap. Silakan ulangi proses pendaftaran.";
        }
    } else {
        echo "Kode OTP salah. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP</title>
    <link rel="stylesheet" href="css/styles1.css">
</head>
<body>
    <header id="header">
        <section>
            <a href=""><img src="img/logo_hello_ngalam-removebg-preview 2.png" alt=""></a>
        </section>
    </header>

    <main>
        <div class="based-content">
            <div class="top-content">
            <form class="form-content" action="index1.php" method="post">
                    <div>
                        <div class="otp-title">OTP Verification</div>
                    </div>
                    <div>
                        <div class="sent-code">We have sent the verification code to your email address</div>
                    </div>
                    <div class="inside-content1">
                        <input type="text" name="number-1" maxlength="1" required>
                    </div>
                    <div class="inside-content2">
                        <input type="text" name="number-2" maxlength="1" required>
                    </div>
                    <div class="inside-content3">
                        <input type="text" name="number-3" maxlength="1" required>
                    </div>
                    <div class="inside-content4">
                        <input type="text" name="number-4" maxlength="1" required>
                    </div>
                    <div>
                        <button name="verify" class="button-continue">Continue</buttonn>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>