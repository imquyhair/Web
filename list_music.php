<?php
session_start();
include 'includes/config.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$limit = 24;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT id, title, artist, image FROM songs ORDER BY id ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$total_sql = "SELECT COUNT(id) AS total FROM songs";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
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

    <div class="container my-5 song-container" data-aos="fade-up">
        <div class="section-header">
            <h5 class="section-titles1">Danh sách bài hát</h5>
        </div>
        <div class="song-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="song-item">
                        <a href="playlist.php?id=<?= $row["id"] ?>" class="song-link">
                            <img src="img/<?= htmlspecialchars($row["image"]) ?>" alt="<?= htmlspecialchars($row["title"]) ?>">
                            <div class="song-info">
                                <span class="song-title"><?= htmlspecialchars($row["title"]) ?></span>
                                <span class="song-artist"><?= htmlspecialchars($row["artist"]) ?></span>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không có bài hát nào.</p>
            <?php endif; ?>
        </div>

        <!-- Bọc phân trang trong div để dễ quản lý -->
        <div class="pagination-wrapper">
            <nav>
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Trước</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Sau &raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

    </div>
</body>

</html>

<?php $conn->close(); ?>