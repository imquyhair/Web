<?php
session_start();

?>

<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài hát | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .sidebar {
            position: fixed;
            top: 74px;
            /* Tăng lên 110px hoặc 120px để chắc chắn không bị che */
            left: 0;
            width: 250px;
            height: 800px;
            /* Trừ đúng chiều cao header */
            padding-top: 20px;
            z-index: 1000;
        }


        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        /* Khoảng cách giữa các mục trong sidebar */
        .nav-pills .nav-link {
            font-weight: bold;
            padding: 12px 15px;
            /* Tăng padding để dễ bấm hơn */
            border-radius: 5px;
        }

        /* Dịch toàn bộ sidebar xuống */
        .sidebar h4 {
            margin-top: 10px;
            /* Thêm khoảng cách giữa tiêu đề và header */
        }

        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            /* Ngăn chặn cuộn ngang */
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            padding-top: 20px;
            overflow-y: auto;
            /* Thêm thanh trượt dọc */
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
            /* Thêm thanh trượt dọc */
        }

        /* Tùy chỉnh thanh trượt */
        ::-webkit-scrollbar {
            width: 8px;
            /* Độ rộng của thanh trượt */
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            /* Màu sắc của thanh trượt */
            border-radius: 10px;
            /* Làm tròn thanh trượt */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Màu sắc khi hover */
        }
    </style>

    <section id="header" class="bg-light shadow-sm sticky-top" data-aos="fade-down">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between py-2">
                <a href="#" id="logo">
                    <img src="img/logomeramusic.jpg" alt="Logo" class="img-fluid">
                </a>

                <div class="search-container">
                    <form action="search.php" method="GET" id="searchForm">
                        <input type="text" name="search" id="searchInput" placeholder="Nhập tên bài hát hoặc nghệ sĩ...">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <nav>
                    <ul id="navbar" class="nav">
                        <li class="nav-item"><a class="nav-link active text-dark fw-bold" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-bold" href="list_music.php">Playlists</a></li>
                        <li class="nav-item"><a class="nav-link text-dark fw-bold" href="about.php">About</a></li>
                        <li class="nav-item">
                            <a class="nav-link text-dark fw-bold" href="#contact-section">Contact</a>
                        </li>
                        <?php if (isset($_SESSION['email'])):
                            $email_parts = explode("@", $_SESSION['email']);
                            $display_name = htmlspecialchars($email_parts[0]);
                        ?>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link admin-box fw-bold" href="admin.php">Admin</a>
                                </li>
                            <?php endif; ?>

                            <li class="nav-item">
                                <a class="nav-link text-dark fw-bold" href="profile.php">
                                    <i class="fa-solid fa-user"></i> <?php echo $display_name; ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-dark" href="logout.php">
                                    <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="login.php">
                                    <i class="fa-solid fa-sign-in-alt"></i> Đăng nhập
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="register.php">
                                    <i class="fa-solid fa-user-plus"></i> Đăng ký
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <div class="sidebar">
        <h4 class="text-white text-center">Admin Panel</h4>
        <a href="admin.php"><i class=></i> Quản lý bài hát</a>
        <a href="users.php"><i class=></i> Quản lý người dùng</a>
    </div>

    <div class="content">
        <h1 class="mb-4">🖥 Quản lý bài hát</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên Bài Hát</th>
                    <th>Tên Tác Giả</th>
                    <th>File Nhạc</th>
                    <th>Thể Loại</th>
                    <th>Tâm Trạng</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM songs";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['artist']}</td>
                                <td>{$row['file']}</td>
                                <td>{$row['genre']}</td>
                                 <td>{$row['mood']}</td>
<td>
    <a href='edit.php?id={$row['id']}' class='btn btn-warning' title='Sửa'>
        <i class='fas fa-edit'></i>
    </a>
    <a href='delete.php?id={$row['id']}' class='btn btn-danger' title='Xóa'>
        <i class='fas fa-trash-alt'></i>
    </a>
</td>

                              </tr>";
                }
                ?>
            </tbody>
        </table>
        <!-- Form để thêm bài hát mới -->
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
    </div>
    </div>
    </div>