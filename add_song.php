<?php
session_start();
include 'includes/config.php'; // Kết nối với cơ sở dữ liệu

// Kiểm tra xem người dùng có đăng nhập và có quyền admin hay không
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Nếu không phải admin, chuyển đến trang login
    exit();
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $genre = $_POST['genre'];
    $mood = $_POST['mood'];

    // Xử lý file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];

        // Đặt tên file và thư mục lưu trữ
        $upload_dir = 'uploads/songs/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Chuyển file vào thư mục music
        $file_path = $upload_dir . basename($file_name);
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Lưu dữ liệu vào cơ sở dữ liệu
            $sql = "INSERT INTO songs (title, artist, file, genre, mood) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $title, $artist, $file_path, $genre, $mood);

            if ($stmt->execute()) {
                // Nếu thêm thành công, chuyển đến trang quản lý bài hát
                header("Location: admin.php");
                exit();
            } else {
                echo "Lỗi khi thêm bài hát: " . $stmt->error;
            }
        } else {
            echo "Có lỗi khi tải file lên!";
        }
    } else {
        echo "Vui lòng chọn file nhạc để tải lên!";
    }
}

?>

<!-- Nếu bạn muốn hiển thị lại form khi có lỗi -->
<div class="content">
    <h2 class="mt-5">Thêm Bài Hát Mới</h2>
    <form action="add_song.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Tên Bài Hát</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="artist" class="form-label">Tên Tác Giả</label>
            <input type="text" class="form-control" id="artist" name="artist" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Chọn File Nhạc</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Thể Loại</label>
            <input type="text" class="form-control" id="genre" name="genre" required>
        </div>
        <div class="mb-3">
            <label for="mood" class="form-label">Tâm Trạng</label>
            <input type="text" class="form-control" id="mood" name="mood" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm Bài Hát</button>
    </form>
</div>