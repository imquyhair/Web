<?php
include 'includes/config.php';

// Lấy ID bài hát từ URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM songs WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Bài hát không tồn tại!");
    }
} else {
    die("Thiếu ID bài hát!");
}

// Cập nhật bài hát
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $file = $_POST['file'];
    $genre = $_POST['genre'];
    $mood = $_POST['mood'];

    $update_sql = "UPDATE songs SET title='$title', artist='$artist', file='$file', genre='$genre', mood='$mood' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location='admin.php';</script>";
    } else {
        echo "Lỗi cập nhật: " . $conn->error;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa bài hát</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 30px;
            color: #007bff;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            margin-left: 10px;
        }
    </style>
</head>

<div class="container">
    <h2>✏️ Chỉnh sửa bài hát</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Tên bài hát:</label>
            <input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="artist" class="form-label">Tên tác giả:</label>
            <input type="text" class="form-control" name="artist" value="<?php echo $row['artist']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">File nhạc:</label>
            <input type="text" class="form-control" name="file" value="<?php echo $row['file']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Thể loại:</label>
            <input type="text" class="form-control" name="genre" value="<?php echo $row['genre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="mood" class="form-label">Tâm trạng:</label>
            <input type="text" class="form-control" name="mood" value="<?php echo $row['mood']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="admin.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>