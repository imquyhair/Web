<?php
session_start();

?>

<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: calc(100vh - 120px);
            /* Điều chỉnh chiều cao */
            background: #343a40;
            position: fixed;
            top: 120px;
            /* Dịch xuống dưới để không bị che */
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
        <a href="admin.php"><i class="fa fa-music"></i> Quản lý bài hát</a>
        <a href="users.php"><i class="fa fa-users"></i> Quản lý người dùng</a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <h1 style="margin-top: 20px;">👤 Quản lý người dùng</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM users";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                               <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                              </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>