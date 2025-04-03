<?php
include 'includes/admin_sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO admin_users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm Admin</title>
</head>
<body>
    <h2>Thêm Admin</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Vai trò:</label>
        <select name="role">
            <option value="moderator">Moderator</option>
            <option value="superadmin">Superadmin</option>
        </select><br>
        <button type="submit">Thêm</button>
    </form>
</body>
</html>
