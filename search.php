<?php
session_start();
include 'includes/config.php';

$search = "";
$result = null;
$sql = "SELECT * FROM songs WHERE category = 'Hiện đang thịnh hành'";
$result_new = $conn->query($sql);

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $search_param = "%$search%";

    $stmt = $conn->prepare("SELECT * FROM songs WHERE title LIKE ? OR artist LIKE ? LIMIT 5");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    echo "<script>alert('Please enter a search keyword!'); window.history.back();</script>";
    exit();
}

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/search.css">
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
                        <input type="text" name="search" id="searchInput" placeholder="Enter song or artist name...">
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
                                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="login.php">
                                    <i class="fa-solid fa-sign-in-alt"></i> Login
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

    <div class="container mt-5">
        <h3 class="text-center">Search Results</h3>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card song-card h-100 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="img/<?= $row["image"] ?>" class="img-fluid rounded-start" alt="<?= $row["title"] ?>">
                                </div>
                                <div class="col-md-8 d-flex flex-column justify-content-center">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $row["title"] ?></h5>
                                        <p class="card-text text-muted">Artist: <?= $row["artist"] ?></p>
                                        <a href="playlist.php?id=<?= $row["id"] ?>" class="btn btn-primary">Play Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-danger fw-bold">No songs found.</p>
        <?php endif; ?>
    </div>
</body>

</html>
