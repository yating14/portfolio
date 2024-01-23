<?php
// 在此之前，确保已连接到数据库并获得了 $user_id 变量

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['blacklist_reason'])) {
    $user_id = $_POST['user_id'];
    $blacklist_reason = $_POST['blacklist_reason'];

    // 执行 SQL 查询以更新用户的 'review' 值为 2，并添加黑名单原因
    require_once('conn.php');

    if ($conn->connect_error) {
        die("資料庫連接失敗: " . $conn->connect_error);
    }

    // 使用单引号将 $user_id 包裹起来
    $updateQuery = "UPDATE users SET review = 2, black_reason = '$blacklist_reason' WHERE user_id = '$user_id'";

    if ($conn->query($updateQuery) === TRUE) {
        // 更新成功后，执行页面重定向
        header("Location: index.html");
        exit;
    } else {
        echo "更新失败: " . $conn->error;
    }

    $conn->close();
} else {
    echo "請求失敗";
}
?>
