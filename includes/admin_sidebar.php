<?php
$servername = "localhost";
$username = "root"; // Thay bằng username của MySQL
$password = ""; // Thay bằng password của MySQL nếu có
$database = "admin";

// Kết nối với MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
