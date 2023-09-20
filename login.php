<?php
session_start();
include_once('config.php');

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT `username`, `password`, `type_id` FROM `accounts` WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $type_id = $row['type_id'];
        $loggedInUsername = $row['username'];

        if ($username === "admin") {
            $type_id = 1; // Cập nhật type_id thành 1 cho người dùng admin
        }

        // Lưu tên tài khoản vào phiên (session)
        $_SESSION['username'] = $loggedInUsername;

        if ($type_id == 1) {
            echo '<script>alert("Đăng nhập thành công! Đang vào trang administrator...");</script>';
            header("Location: admin.php");
            exit(); // Dừng kịch bản để chuyển hướng hoàn toàn
        } else {
            header("Location: index.php");
            exit(); // Dừng kịch bản để chuyển hướng hoàn toàn
        }
    } else {
        // Đăng nhập không thành công
        echo '<script>alert("Sai tài khoản hoặc mật khẩu!");</script>';
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
    <header class="header">
        <section class="flex">
            <a class="logo" href="index.php">Tine<span>images</span></a>
        </section>
    </header>

    <div class="container">
        <h2>Đăng Nhập</h2>
        <form method="POST" action="login.php">
            <label for="username">Tên đăng nhập:</label>
            <input type="varchar" name="username" id="username" required>
            <label for="password">Mật khẩu:</label>
            <input type="varchar" name="password" id="password" required>
            <input type="number" name="type_id" value="0" hidden>
            <a href="signup.php">Đăng ký tài khoản</a><br>
            <input type="submit" value="Đăng Nhập">
        </form>
    </div>
</body>

</html>

<style>
body {
    font-family: cursive, Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 400px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="varchar"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 10px;
}

input[type="submit"] {
    width: 100%;
    background-color: #4CAF50;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
}
</style>
