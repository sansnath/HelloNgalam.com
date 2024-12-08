<?php
    session_start(); 
    include "data.php";

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM akun WHERE email_akun = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_akun'])) {
                $_SESSION['email'] = $email;
                header("Location: http://localhost/HelloNgalam.com/HomePage_After/index1.php");
                exit();
            } else {
                $error = "Email atau password salah!";
            }
        } else {
            $error = "Email atau password salah!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles2.css">
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
                <form class="form-image">
                    <a href=""><img src="img/large-alun-alun-tugu-kota-malang-8c27c18ca551dc2bd8c5629b1a2ad7c7 2.jpg" alt=""></a>
                </form>
                <form class="form-content" action = "index2.php" method="POST" >
                    <div>
                        <div class="login-title">Login</div>
                    </div>
                    <div class="form-input-email">
                        <input type="email" name="email" required>
                        <label>Email</label>
                        <a href=""><img src="img/Vector (1).svg" alt=""></a>
                    </div>
                    <div class="form-input-password">
                        <input type="password" name="password" required>
                        <label>Password</label>
                        <a href=""><img src="img/Eye.png" alt=""></a>
                    </div>
                    <div>
                        <button type="submit" name="login" class="button-login">Login</button>
                    </div>
                    <div>
                        <a href="http://localhost/HelloNgalam.com/Sign%20Up/" class="create-account">Create Account</a>
                        <a href="http://localhost/HelloNgalam.com/Forgot%20Password/index5.php" class="forget-pw">Forget Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>