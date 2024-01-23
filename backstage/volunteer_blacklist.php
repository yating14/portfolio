<?php
require_once('conn.php');

$sql = "SELECT * FROM `users` WHERE review='2'";
$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res); // 總共有幾筆資料

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    // 更新用户的review值从2到0
    $update_sql = "UPDATE `users` SET review='0' WHERE user_id='$user_id'";
    mysqli_query($conn, $update_sql);
     // 执行重定向到index.html
     header('Location: index.html');
     exit; // 确保脚本终止，以避免进一步输出
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <h1>黑名單</h1><hr>
    <h2>共<?php echo $count?>筆</h2>
    <div class="showdata">
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>#</th>
                    <th>姓名</th>
                    <th>編號</th>
                    <th>封鎖原因</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $cnt = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $user_id = $row['user_id'];
                    echo "<tr>
                        <td>{$cnt}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['black_reason']}</td>
                        <td>
                            <form action='volunteer_blacklist.php' method='post'>
                                <input type='hidden' name='user_id' value='$user_id'>
                                <button class='btn' type='submit'>解除</button>
                            </form>
                        </td>";
                    $cnt++;
                }
                mysqli_close($conn);
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>