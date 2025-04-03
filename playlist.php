<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php?message=Bạn cần đăng nhập để nghe nhạc!");
    exit();
}

// Kết nối đến MySQL
$conn = new mysqli("localhost", "root", "", "music_db");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID bài hát từ URL
$song_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM songs WHERE id = ?");
$stmt->bind_param("i", $song_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $song = $result->fetch_assoc();
} else {
    die("Bài hát không tồn tại.");
}

// Gợi ý bài hát ngẫu nhiên
$suggested_sql = "SELECT * FROM songs ORDER BY RAND() LIMIT 5";
$suggested_result = $conn->query($suggested_sql);

$conn->close();
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($song["title"]); ?> - Nghe Nhạc</title>
    <link rel="stylesheet" href="css/playlist.css">
    <style>
        .song-lyrics-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .song-lyrics {
            height: 100px;
            width: 40%;
            overflow: hidden;
            text-align: center;
            font-size: 18px;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            position: relative;
        }

        .lyric-line {
            transition: color 0.3s ease-in-out, background-color 0.3s ease-in-out;
            opacity: 0.5;
            padding: 5px 0;
        }

        .active-line {
            color: #ff4081;
            opacity: 1;
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="index.php" style="text-decoration: none; color: inherit;">
            <h2>Mera MP3</h2>
            <a href="#">🎵 Playlist</a>
            <a href="#">🔥 Favourite</a>
            <a href="#">📊 #merachart</a>
    </div>

    <div class="main-content">
        <img src="img/<?php echo htmlspecialchars($song["image"]); ?>" alt="<?php echo htmlspecialchars($song["title"]); ?>">
        <h2 class="song-title"><?php echo htmlspecialchars($song["title"]); ?></h2>
        <p class="song-artist"><?php echo htmlspecialchars($song["artist"]); ?></p>

        <div class="music-player">
            <div class="player-info">
                <img src="img/<?php echo htmlspecialchars($song["image"]); ?>" alt="Now Playing">
                <div>
                    <p><?php echo htmlspecialchars($song["title"]); ?></p>
                    <p><?php echo htmlspecialchars($song["artist"]); ?></p>
                </div>
            </div>

            <div class="player-controls">
                <audio id="audioPlayer" controls autoplay>
                    <source src="music/<?php echo htmlspecialchars($song["file"]); ?>" type="audio/mpeg">
                    Trình duyệt không hỗ trợ phát nhạc.
                </audio>
            </div>
        </div>

        <div class="playlist">
            <h3>Song List</h3>
            <?php while ($row = $suggested_result->fetch_assoc()) : ?>
                <a href="playlist.php?id=<?= $row["id"] ?>" class="playlist-item">
                    <img src="img/<?php echo htmlspecialchars($row["image"]); ?>" alt="<?php echo htmlspecialchars($row["title"]); ?>">
                    <div>
                        <p><?php echo htmlspecialchars($row["title"]); ?></p>
                        <p><?php echo htmlspecialchars($row["artist"]); ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        const audio = document.getElementById("audioPlayer");
        const lyricLines = document.querySelectorAll(".lyric-line");

        audio.addEventListener("timeupdate", () => {
            const currentTime = audio.currentTime;
            let activeLine = null;

            lyricLines.forEach((line) => {
                const lineTime = parseFloat(line.getAttribute("data-time"));
                const nextLine = line.nextElementSibling;
                const nextLineTime = nextLine ? parseFloat(nextLine.getAttribute("data-time")) : Infinity;

                if (currentTime >= lineTime && currentTime < nextLineTime) {
                    activeLine = line;
                }
                line.classList.remove("active-line");
            });

            if (activeLine) {
                activeLine.classList.add("active-line");
                document.getElementById("lyrics").scrollTo({
                    top: activeLine.offsetTop - document.getElementById("lyrics").offsetHeight / 2,
                    behavior: "smooth"
                });
            }
        });
    </script>
</body>

</html>