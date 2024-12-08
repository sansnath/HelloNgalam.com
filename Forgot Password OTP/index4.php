<?php
session_start();
include "data.php";

if (isset($_POST['verify'])) {
    $otpInput = $_POST['number-1'] . $_POST['number-2'] . $_POST['number-3'] . $_POST['number-4'];

    if ($otpInput == $_SESSION['otp']) {
        if (isset($_SESSION['email'], $_SESSION['new_password'])) {
            $email = $_SESSION['email'];
            $newPassword = $_SESSION['new_password'];

            $updatePasswordSql = "UPDATE akun SET password_akun = '$newPassword' WHERE email_akun = '$email'";

            if (mysqli_query($db, $updatePasswordSql)) {
                echo "Password berhasil diperbarui!";
                header("Location: http://localhost/HelloNgalam.com/Login/index2.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($db);
            }
        } else {
            echo "Data session tidak lengkap. Silakan ulangi proses reset password.";
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
    <title>Forgot Password OTP</title>
    <link rel="stylesheet" href="css/styles4.css">
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
                <form method="post" class="form-content">
                    <div>
                        <div class="fp-title">Forgot Password?</div>
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
                        <button name="verify" class="button-continue">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>