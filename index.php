<?php
session_start();
include 'includes/config.php';

$sql = "SELECT * FROM songs WHERE category = 'Hiện đang thịnh hành'";
$result_new = $conn->query($sql);
$sql = "SELECT * FROM chills WHERE genre = 'chill'";
$result_chill = $conn->query($sql);
$sql = "SELECT * FROM songs WHERE category = 'Danh sách bài hát'";
$result_song = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
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
                        <input type="text" name="search" id="searchInput" placeholder="Enter song name or artist...">
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
                                    <i class="fa-solid fa-sign-out-alt"></i> Log out
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="login.php">
                                    <i class="fa-solid fa-sign-in-alt"></i> Log in
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="register.php">
                                    <i class="fa-solid fa-user-plus"></i> Register
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <section id="slider" class="my-5" data-aos="fade-up">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/img4.png" class="d-block w-100" alt="Image 1">
                </div>
                <div class="carousel-item">
                    <img src="img/img2.png" class="d-block w-100" alt="Image 2">
                </div>
                <div class="carousel-item">
                    <img src="img/img3.jpg" class="d-block w-100" alt="Image 3">
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <h1 class="section-title text-center mb-4" data-aos="fade-up">Currently trending</h1>
        <div class="trending-songs position-relative" data-aos="fade-up">

            <button class="btn-slide left"><i class="fas fa-chevron-left"></i></button>

            <div class="songs-container">
                <div class="songs-wrapper">
                    <?php if ($result_new && $result_new->num_rows > 0): ?>
                        <?php while ($row = $result_new->fetch_assoc()): ?>
                            <div class="song-card" data-aos="fade-up">
                                <a href="playlist.php?id=<?= $row["id"] ?>" class="song-link">
                                    <div class="play-icon"><i class="fas fa-play"></i></div>
                                    <img src="img/<?= $row["image"] ?>" alt="<?= $row["title"] ?>">
                                </a>
                                <h5><?= $row["title"] ?></h5>
                                <p><?= $row["artist"] ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Không có bài hát nào.</p>
                    <?php endif; ?>
                </div>
                <button class="btn-slide right"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <h1 class="section-title text-center mb-4" data-aos="fade-up">How are you today</h1>
        <div class="songs-container">
            <div class="songs-wrapper">
                <?php if ($result_chill && $result_chill->num_rows > 0): ?>
                    <?php while ($row = $result_chill->fetch_assoc()): ?>
                        <div class="song-card" data-aos="fade-up">
                            <a href="chill-music.php" class="btn btn-secondary">
                                <img src="img/chill.jpg" alt="Chill-Lofi">
                            </a>
                            <h5>Chill-Lofi</h5>
                            <p>Soft Melodies</p>
                        </div>
                        <div class="song-card" data-aos="fade-up">
                            <a href="rain_mood.php" class="btn btn-secondary">
                                <img src="img/rainmood.jpg" alt="Rain Mood">
                            </a>
                            <h5>Rain Mood</h5>
                            <p>Soft Sad Melodies</p>
                        </div>
                        <div class="song-card" data-aos="fade-up">
                            <a href="/rap.php" class="btn btn-secondary">
                                <img src="img/rap.jpg" alt="Rap">
                            </a>
                            <h5>Rap</h5>
                            <p>Hip-Hop Songs</p>
                        </div>
                        <div class="song-card" data-aos="fade-up">
                            <a href="pop.php" class="btn btn-secondary">
                                <img src="img/dynamic.jpg" alt="Pop">
                            </a>
                            <h5>Pop</h5>
                            <p>Contemporary Songs</p>
                        </div>
                        <div class="song-card" data-aos="fade-up">
                            <a href="thinking.php" class="btn btn-secondary">
                                <img src="img/thinking.jpg" alt="Thinking">
                            </a>
                            <h5>Thinking</h5>
                            <p>Reflection Songs</p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No songs.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="container my-5" data-aos="fade-up">
        <div class="section-header">
            <h5 class="section-titles">Song list</h5>
            <a href="list_music.php" class="view-all">All<i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="song-list">
            <?php
            $count = 0; // Thêm bộ đếm
            if ($result_song && $result_song->num_rows > 0):
            ?>
                <?php while ($row = $result_song->fetch_assoc()): ?>
                    <?php if ($count >= 12) break; // Dừng khi đủ 12 bài hát 
                    ?>
                    <div class="song-item">
                        <a href="playlist.php?id=<?= $row["id"] ?>" class="song-link">
                            <img src="img/<?= $row["image"] ?>" alt="<?= $row["title"] ?>">
                            <div class="song-info">
                                <span class="song-title"><?= htmlspecialchars($row["title"]) ?></span>
                                <span class="song-artist"><?= htmlspecialchars($row["artist"]) ?></span>
                            </div>
                        </a>
                    </div>
                    <?php $count++; // Tăng biến đếm 
                    ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No songs.</p>
            <?php endif; ?>
        </div>
    </div>


    <li class="nav-item">
        <a href="#" onclick="toggleChat(event)">
            <img src="../img/bot.jpg" alt="Chat" class="icon">
        </a>
    </li>
    <div id="chatbot">
        <div id="chatbot-header">
            <span>Mera AI</span>
            <button onclick="toggleChat()">✖</button>
        </div>
        <div id="chatbot-messages"></div>
        <div id="chatbot-input">
            <input type="text" id="chatbot-text" placeholder="Enter question...">
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>

    <footer id="contact-section" class="footer1 bg-dark text-light py-4">

        <div class="container2">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="fw-bold">About Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.html" class="text-light">About</a></li>
                        <li><a href="jobs.html" class="text-light">Jobs</a></li>
                        <li><a href="news.html" class="text-light">News</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Community</h5>
                    <ul class="list-unstyled">
                        <li><a href="artists.html" class="text-light">For Artists</a></li>
                        <li><a href="developers.html" class="text-light">Developers</a></li>
                        <li><a href="ads.html" class="text-light">Advertisements</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="help.html" class="text-light">Help Center</a></li>
                        <li><a href="mobile-app.html" class="text-light">Mobile Apps</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Connect with us</h5>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
    </script>
    <script src="js/javacript.js"></script>
</body>

</html>