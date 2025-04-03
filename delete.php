<?php
session_start();
include 'includes/config.php';

// Kiểm tra xem có truyền ID bài hát cần xóa không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra bài hát có tồn tại trong cơ sở dữ liệu không
    $check_sql = "SELECT * FROM songs WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu bài hát tồn tại, tiến hành xóa
        $delete_sql = "DELETE FROM songs WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);

        if ($delete_stmt->execute()) {
            // Nếu xóa thành công, lưu thông báo vào session và chuyển hướng về trang quản lý bài hát (admin)
            $_SESSION['message'] = 'Bài hát đã được xóa thành công!';
            $_SESSION['msg_type'] = 'success';
        } else {
            // Nếu có lỗi khi xóa
            $_SESSION['message'] = 'Có lỗi xảy ra khi xóa bài hát!';
            $_SESSION['msg_type'] = 'danger';
        }
    } else {
        // Nếu không tìm thấy bài hát
        $_SESSION['message'] = 'Bài hát không tồn tại!';
        $_SESSION['msg_type'] = 'danger';
    }

    // Chuyển hướng lại trang quản lý bài hát (admin)
    header("Location: admin.php");
    exit();
} else {
    // Nếu không truyền ID
    $_SESSION['message'] = 'ID không hợp lệ!';
    $_SESSION['msg_type'] = 'danger';
    header("Location: admin.php");
    exit();
}
?>
