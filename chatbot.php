<?php
header("Content-Type: application/json");

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["response" => "Lỗi kết nối database."]);
    exit;
}

// Lấy tin nhắn từ người dùng
$data = json_decode(file_get_contents("php://input"), true);
$user_message = trim($data["message"] ?? "");

if (empty($user_message)) {
    echo json_encode(["response" => "Vui lòng nhập câu hỏi của bạn."]);
    exit;
}

// Kiểm tra xem người dùng có yêu cầu tiếng Việt không
$is_vietnamese = preg_match('/\b(tiếng việt|trả lời bằng tiếng việt|trả lời tiếng việt|việt nam)\b/i', $user_message);

// Truy vấn danh sách bài hát từ MySQL
$sql = "SELECT id, title, artist, genre, mood, file FROM songs";
$result = $conn->query($sql);
$song_data = [];
$song_map = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $song_id = $row['id'];
        $song_title = $row['title'];

        $song_map[$song_title] = $song_id;
        $song_data[] = "🎵 <b>{$row['title']}</b> - {$row['artist']} | 🎼 Thể loại: {$row['genre']} | 🎭 Tâm trạng: {$row['mood']} | 🔗 <a href='playlist.php?id={$song_id}' target='_self'>Nghe ngay</a>";
    }
}
$conn->close();

// Chuyển danh sách thành dạng văn bản
$song_text = implode("\n", $song_data);

// Nội dung gửi lên Gemini
$language_prompt = "Answer in English by default. If the user asks in Vietnamese, reply in Vietnamese.";
if ($is_vietnamese) {
    $language_prompt = "Trả lời bằng tiếng Việt theo yêu cầu của người dùng.";
}

$api_key = "AIzaSyCCF1HPTunBVsbRM42JtYZhVKb3i8o2iVk";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=$api_key";

$post_data = json_encode([
    "contents" => [
        ["parts" => [
            ["text" => "$language_prompt\n\nHere is the list of available songs:\n$song_text\n\nUser's question: $user_message\n\nSuggest a suitable song based on the user's request."]
        ]]
    ]
]);

// Gửi request tới Gemini
$headers = ["Content-Type: application/json"];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["response" => "Lỗi cURL: " . curl_error($ch)]);
    exit;
}
curl_close($ch);

// Ghi log phản hồi để debug
file_put_contents("gemini_log.txt", $response);

// Xử lý phản hồi từ Gemini
$response_data = json_decode($response, true);
if (isset($response_data["candidates"][0]["content"]["parts"][0]["text"])) {
    $reply = $response_data["candidates"][0]["content"]["parts"][0]["text"];

    foreach ($song_map as $title => $id) {
        if (strpos($reply, $title) !== false) {
            $reply = str_replace(
                $title,
                "$title<br> <a href='/playlist.php?id=$id' target='_self'><b>Listen now</b></a>",
                $reply
            );
        }
    }
} else {
    $reply = $is_vietnamese ? "Xin lỗi, tôi không hiểu câu hỏi của bạn hoặc có lỗi xảy ra khi xử lý." : "Sorry, I couldn't understand your question or an error occurred.";
}

// Định dạng phản hồi để hiển thị đẹp hơn
$reply = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $reply);
$reply = nl2br($reply);

echo json_encode(["response" => $reply]);
