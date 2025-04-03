<?php
session_start();
include 'includes/config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT id, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Lỗi SQL: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $user_email, $hashed_password, $role);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $user_email;
                $_SESSION['role'] = $role; // Lưu role vào session

                header("Location: index.php"); // Chuyển hướng đến trang chủ
                exit();
            } else {
                $error = "Sai mật khẩu!";
            }
        } else {
            $error = "Email không tồn tại!";
        }

        $stmt->close();
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
    <title>Đăng nhập - Music App</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Đăng nhập vào Mera MP3</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="Nhập email">

            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Đăng nhập</button>
        </form>

        <p><a href="#">Quên mật khẩu?</a></p>
        <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký tại đây</a></p>
    </div>
</body>

</html>
