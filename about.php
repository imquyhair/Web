<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "Session không tồn tại! Hãy kiểm tra lại.";
} else {
    echo "Session tồn tại: " . $_SESSION['email'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài hát | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body>
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

    <section class="about-container">
        <h1 class="about-title">Về MeraMusic</h1>
        <p class="about-subtitle">Trải nghiệm âm nhạc tuyệt vời với thư viện nhạc đa dạng</p>
        <div class="about-content">
            <div class="about-image">
                <img src="img/about-banner.jpg" alt="Giới thiệu" class="img-fluid rounded">
            </div>
            <div class="about-text">
                <p>MeraMusic là nền tảng nghe nhạc trực tuyến mang đến những giai điệu hay nhất cho người dùng. Với danh sách nhạc phong phú từ nhiều thể loại, chúng tôi cam kết đem lại trải nghiệm âm nhạc tuyệt vời nhất.</p>
                <ul>
                    <li>Hàng nghìn bài hát từ nhiều thể loại</li>
                    <li>Gợi ý nhạc thông minh theo sở thích</li>
                    <li>Giao diện thân thiện, dễ sử dụng</li>
                    <li>Kết nối và chia sẻ với cộng đồng yêu nhạc</li>
                </ul>
            </div>
        </div>
    </section>

    <footer id="contact-section" class="footer1 bg-dark text-light py-4">

        <div class="container2">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="fw-bold">Về chúng tôi</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.html" class="text-light">Giới thiệu</a></li>
                        <li><a href="jobs.html" class="text-light">Việc làm</a></li>
                        <li><a href="news.html" class="text-light">Tin tức</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Cộng đồng</h5>
                    <ul class="list-unstyled">
                        <li><a href="artists.html" class="text-light">Dành cho nghệ sĩ</a></li>
                        <li><a href="developers.html" class="text-light">Nhà phát triển</a></li>
                        <li><a href="ads.html" class="text-light">Quảng cáo</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Hỗ trợ</h5>
                    <ul class="list-unstyled">
                        <li><a href="help.html" class="text-light">Trung tâm trợ giúp</a></li>
                        <li><a href="mobile-app.html" class="text-light">Ứng dụng di động</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Kết nối với chúng tôi</h5>
                    <div class="social-icons1">
                        <a href="#" class="text-light me-2"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-3 border-light">
            <p class="text-center mb-0">© 2025 MeraMusic. All Rights Reserved.</p>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>