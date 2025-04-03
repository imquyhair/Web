<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php?message=Bạn cần đăng nhập để nghe nhạc!");
    exit();
}

include 'includes/config.php'; // Kết nối database

// Truy vấn danh sách bài hát theo genre
$genre = 'Lo-fi'; // Có thể thay đổi genre theo trang
$sql = "SELECT * FROM songs WHERE genre = '$genre'";
$result_songs = $conn->query($sql);

// Chỉ hiển thị các bài hát Lo-fi đang thịnh hành
$sql = "SELECT * FROM songs WHERE genre = 'Lo-fi' AND category = 'Hiện đang thịnh hành'";
$result_new = $conn->query($sql);

// Chỉ hiển thị các bài hát thuộc genre 'Lo-fi' trong danh sách bài hát
$sql = "SELECT * FROM songs WHERE genre = 'Lo-fi'";
$result_song = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($genre) ?> Chill</title>
    <link rel="stylesheet" href="css/chill-music.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container my-5">
        <h1 class="section-title text-center mb-4" data-aos="fade-up">Hiện đang thịnh hành</h1>
        <div class="trending-songs position-relative" data-aos="fade-up">

            <button class="btn-slide left"><i class="fas fa-chevron-left"></i></button>

            <div class="songs-container">
                <div class="songs-wrapper">
                    <?php if ($result_new && $result_new->num_rows > 0): ?>
                        <?php while ($row = $result_new->fetch_assoc()): ?>
                            <?php if ($row["genre"] === 'Lo-fi'): ?>
                                <div class="song-card" data-aos="fade-up">
                                    <a href="playlist.php?id=<?= $row["id"] ?>" class="song-link">
                                        <div class="play-icon"><i class="fas fa-play"></i></div>
                                        <img src="img/<?= $row["image"] ?>" alt="<?= $row["title"] ?>">
                                    </a>
                                    <h5><?= $row["title"] ?></h5>
                                    <p><?= $row["artist"] ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Không có bài hát nào.</p>
                    <?php endif; ?>
                </div>
                <button class="btn-slide right"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <div class="container my-5" data-aos="fade-up">
        <div class="section-header">
            <h5 class="section-titles">Danh sách bài hát Lo-fi</h5>
            <a href="#" class="view-all">TẤT CẢ <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="song-list">
            <?php if ($result_song && $result_song->num_rows > 0): ?>
                <?php while ($row = $result_song->fetch_assoc()): ?>
                    <?php if ($row["genre"] === 'Lo-fi'): ?>
                        <div class="song-item">
                            <a href="playlist.php?id=<?= $row["id"] ?>" class="song-link">
                                <img src="img/<?= $row["image"] ?>" alt="<?= $row["title"] ?>">
                                <div class="song-info">
                                    <span class="song-title"><?= htmlspecialchars($row["title"]) ?></span>
                                    <span class="song-artist"><?= htmlspecialchars($row["artist"]) ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không có bài hát nào.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        AOS.init();

        function playSong(audioSrc, title, artist, imageSrc) {
            document.getElementById('audio-source').src = audioSrc;
            document.getElementById('audio-player').load();
            document.getElementById('audio-player').play();

            document.getElementById('current-song-title').innerText = title;
            document.getElementById('current-song-artist').innerText = artist;
            document.getElementById('current-song-img').src = imageSrc;
        }
    </script>
</body>

</html>