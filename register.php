<?php
include 'includes/config.php';  // Kết nối database

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Kiểm tra email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email đã được sử dụng!";
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Thêm người dùng vào database
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);
            if ($stmt->execute()) {
                $_SESSION['email'] = $email;
                header("Location: index.php"); // Chuyển hướng sau khi đăng ký thành công
                exit();
            } else {
                $error = "Lỗi đăng ký!";
            }
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Music App</title>
    <link rel="stylesheet" href="css/register.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="register-container">
        <h2>Đăng ký để bắt đầu nghe</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="email">Địa chỉ email</label>
            <input type="email" id="email" name="email" required placeholder="name@domain.com">

            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Tiếp theo</button>
        </form>

        <hr>
        <p>hoặc</p>

        <button class="social-login google"><i class="fa-brands fa-google"></i> Đăng ký bằng Google</button>
        <button class="social-login facebook"><i class="fa-brands fa-facebook"></i> Đăng ký bằng Facebook</button>
        <button class="social-login apple"><i class="fa-brands fa-apple"></i> Đăng ký bằng Apple</button>

        <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a></p>
    </div>
</body>

</html>