<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["response" => "❌ Lỗi kết nối database."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_message = trim($data["message"] ?? "");

if (empty($user_message)) {
    echo json_encode(["response" => "⚠️ Bạn muốn nghe nhạc theo tâm trạng hoặc thể loại nào?"]);
    exit;
}

// Danh sách thể loại phổ biến
$genres = ["Rap", "Pop", "Rock", "Jazz", "Classical", "EDM", "Hip-hop", "Ballad", "Lo-fi", "R&B", "Indie", "Metal"];
$user_message_lower = strtolower($user_message);
$selected_genre = "";

// Kiểm tra nếu người dùng tìm theo thể loại
foreach ($genres as $genre) {
    if (stripos($user_message, $genre) !== false) {
        $selected_genre = $genre;
        break;
    }
}

if (!empty($selected_genre)) {
    // Tìm nhạc theo thể loại
    $sql = "SELECT title, artist, file FROM songs WHERE genre LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $selected_genre . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    $song_data = [];
    while ($row = $result->fetch_assoc()) {
        $song_data[] = "🎵 <b>{$row['title']}</b> - {$row['artist']} ▶ <a href='songs/{$row['file']}'>Nghe ngay</a>";
    }

    $conn->close();

    if (empty($song_data)) {
        echo json_encode(["response" => "❌ Không tìm thấy bài hát thuộc thể loại '$selected_genre'."]);
    } else {
        $song_list = implode("\n", $song_data);
        echo json_encode(["response" => "🎶 Đây là một số bài hát thuộc thể loại <b>$selected_genre</b>:\n\n$song_list"]);
    }
    exit;
}

// Từ điển phân tích cảm xúc theo từ khóa
$mood_keywords = [
    "buồn" => ["mất", "chia tay", "đau lòng", "khóc", "nhớ", "tổn thương"],
    "vui vẻ" => ["cười", "tận hưởng", "hào hứng", "ngày nắng", "hạnh phúc"],
    "lãng mạn" => ["tình yêu", "yêu", "nhớ người yêu", "hẹn hò"],
    "thư giãn" => ["nghỉ ngơi", "chill", "thư giãn", "yên bình"],
    "hào hứng" => ["phấn khích", "sôi động", "cuồng nhiệt"],
    "năng động" => ["tập gym", "nhảy", "chạy bộ"]
];

// Xác định cảm xúc từ câu người dùng
$best_match = "Vui vẻ"; // Mặc định nếu không tìm thấy

foreach ($mood_keywords as $mood => $keywords) {
    foreach ($keywords as $keyword) {
        if (strpos($user_message_lower, $keyword) !== false) {
            $best_match = ucfirst($mood);
            break 2;
        }
    }
}

// Truy vấn bài hát phù hợp với tâm trạng
$sql = "SELECT title, artist, file FROM songs WHERE mood LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%" . $best_match . "%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();

$song_data = [];
while ($row = $result->fetch_assoc()) {
    $song_data[] = "🎵 <b>{$row['title']}</b> - {$row['artist']} ▶ <a href='songs/{$row['file']}'>Nghe ngay</a>";
}

$conn->close();

// Tạo phản hồi chatbot
if (empty($song_data)) {
    echo json_encode(["response" => "❌ Không tìm thấy bài hát phù hợp với tâm trạng '$best_match'."]);
} else {
    $song_list = implode("\n", $song_data);
    echo json_encode(["response" => "Bạn đang cảm thấy <b>$best_match</b>? Hãy thử một số bài hát này nhé! 🎶\n\n$song_list"]);
}
