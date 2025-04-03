<?php
$servername = "localhost"; // Hoặc IP của máy chủ database
$username = "root"; // Thay bằng username của bạn
$password = ""; // Nếu có mật khẩu thì điền vào đây
$database = "admin"; // Thay bằng tên database của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập UTF-8 để hiển thị tiếng Việt đúng cách
$conn->set_charset("utf8mb4");
?>
