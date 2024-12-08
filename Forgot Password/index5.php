<?php
session_start();
include "data.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['continue'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];  

    if ($new_password !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok.";
        exit();
    }

    $sql = "SELECT * FROM akun WHERE email_akun = '$email'"; 
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email; 
        $_SESSION['new_password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $otp = rand(1000, 9999); 

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sitompulsamuel625@gmail.com';
            $mail->Password = 'dgqr ptmm aqie ntrn';  
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('sitompulsamuel625@gmail.com', 'Hello Ngalam');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP untuk Reset Password Akun Anda';
            $mail->Body = "<p>Halo!</p><p>Kode OTP Anda adalah <strong>$otp</strong>. Silakan masukkan kode ini untuk mengganti password akun anda.</p>";

            $mail->send();

            $_SESSION['otp'] = $otp;  
            header("Location: http://localhost/HelloNgalam.com/Forgot%20Password%20OTP/index4.php");
            exit();
        } catch (Exception $e) {
            echo "Error: Email tidak dapat dikirim. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email tidak terdaftar di sistem kami.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="http://localhost/HelloNgalam.com/Forgot%20Password/css/styles5.css">
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
                <form class="form-content" action="index5.php" method="POST">
                    <div>
                        <div class="fp-title">Forgot Password?</div>
                    </div>
                    <div>
                        <div class="enter-new-password">Please enter your email and new password</div>
                    </div>
                    <div class="form-input-new-password">
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="form-input-new-password">
                        <input type="password" name="new_password" required>
                        <label>New Password</label>
                    </div>
                    <div class="form-input-re-enter-password">
                        <input type="password" name="confirm_password" required>
                        <label>Re-Enter Password</label>
                    </div>
                    <div>
                        <button type="submit" name="continue" class="button-continue">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>