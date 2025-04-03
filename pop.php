<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php?message=Bạn cần đăng nhập để nghe nhạc!");
    exit();
}
include 'includes/config.php'; // Kết nối database

// Lấy nhạc theo thể loại Pop
$genre = 'Pop';

// Truy vấn danh sách bài hát theo thể loại
$sql = "SELECT * FROM songs WHERE genre = '$genre'";

$result_songs = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($genre) ?> Music</title>
    <link rel="stylesheet" href="css/chill-music.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>

<body>
    
    <div class="songs-container">
        <div class="songs-wrapper">
            <?php if ($result_songs && $result_songs->num_rows > 0): ?>
                <?php while ($row = $result_songs->fetch_assoc()): ?>
                    <div class="song-card"
                        data-aos="fade-up"
                        onclick="playSong(
                            'music/<?= htmlspecialchars($row['audio'] ?? '') ?>',
                            '<?= htmlspecialchars($row['title']) ?>',
                            '<?= htmlspecialchars($row['artist'] ?? 'Unknown') ?>',
                            'img/<?= htmlspecialchars($row['image']) ?>'
                        )">

                        <a href="playlist.php?id=<?= $row['id'] ?>&genre=<?= urlencode($genre) ?>" class="song-link">
                            <img src="img/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                        </a>
                        <h5><?= htmlspecialchars($row['title']) ?></h5>
                        <p><?= htmlspecialchars($row['artist'] ?? 'Unknown') ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không có bài hát nào thuộc thể loại này.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        AOS.init(); // Khởi động hiệu ứng

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