<?php
require_once('conn.php');

if (!empty($_GET['username'])) {
    $select = $_GET['username'];
    $sql = "SELECT users.*, users_evaluate.*, activity.* FROM users
    JOIN users_evaluate ON users.user_id = users_evaluate.user_id
    JOIN activity ON users_evaluate.activity_id = activity.activity_id 
    WHERE username LIKE '%$select%';";
} elseif (!empty($_GET['eventname'])) {
    $select = $_GET['eventname'];
    $sql = "SELECT users.*, users_evaluate.*, activity.* FROM users
    JOIN users_evaluate ON users.user_id = users_evaluate.user_id
    JOIN activity ON users_evaluate.activity_id = activity.activity_id 
    WHERE event_name LIKE '%$select%';";
} elseif (!empty($_GET['startdate']) && !empty($_GET['enddate'])) {
    $select1 = $_GET['startdate'];
    $select2 = $_GET['enddate'];
    $sql = "SELECT users.*, users_evaluate.*, activity.* FROM users
    JOIN users_evaluate ON users.user_id = users_evaluate.user_id
    JOIN activity ON users_evaluate.activity_id = activity.activity_id 
    WHERE time BETWEEN '$select1' AND '$select2'";
} else {
    $sql = "SELECT users.*, users_evaluate.*, activity.*
    FROM users
    JOIN users_evaluate ON users.user_id = users_evaluate.user_id
    JOIN activity ON users_evaluate.activity_id = activity.activity_id;";
}

if (isset($_POST['share'])) {
    $user_id = $_POST['user_id'];
    $activity_id = $_POST['activity_id'];
    $username = $_POST['username'];
    $sql_select = "SELECT content FROM users_evaluate WHERE user_id = ? AND activity_id = ?";
    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("si", $user_id, $activity_id);
    $stmt->execute();
    $stmt->bind_result($content);

    if ($stmt->fetch()) {
        $stmt->close(); // Close the first statement

        $sql_insert = "INSERT INTO `share` (share_name, comment) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ss", $username, $content);

        if ($stmt->execute()) {
            echo '<script>alert("成功加入至志工分享"); window.location.href = "index.html";</script>';
        } else {
            echo '<script>alert("加入失敗");</script>';
        }
    }
}

$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res); // Total number of rows in the result set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>志工評價</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <h1>志工評價</h1>
    <hr>
    <div class="searchbar">
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <td>篩選</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form action="volunteer_evaluation.php" method="$_GET" onsubmit="searchVolunteers(event)">
                            <p>志工姓名：<input class="search" type="text" name="username" placeholder="請輸入志工姓名"></p><br>
                            <p>活動名稱：<input class="search" type="text" name="eventname" placeholder="請輸入活動名稱"></p><br>
                            <p>上傳日期區間：<input type="date" name="startdate"> ~ <input type="date" name="enddate"></p><br>
                            <button type="submit" name="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>
    <?php
    echo  "<h2>共 $count 筆</h2>";
    ?>
    <div class="showdata">
        <table border="1" cellpadding="10" style="text-align: center;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>姓名</th>
                    <th>編號</th>
                    <th>活動名稱</th>
                    <th>活動編號</th>
                    <th>上傳時間</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $user_id = $row['user_id'];
                    $activity_id = $row['activity_id'];
                    $username = $row['username'];
                    echo "<tr>
                        <td>{$cnt}</td>
                        <td>{$row['username']}</td>
                        <td><span class='name_link' onclick=loadContent('volunteer_list_details.php?user_id={$user_id}&volunteers_share=1')>{$row['user_id']}</span></td>
                        <td>{$row['event_name']}</td>
                        <td><span class='name_link' onclick=loadContent('activity_list_details.php?activity_id={$activity_id}&volunteers_share=1')>{$row['activity_id']}</span></td>
                        <td>{$row['time']}</td>
                        <td><a href='#' onclick=loadContent('Volunteer_evaluation_details.php?user_id={$user_id}&username={$username}&activity_id={$activity_id}')><button class='btn'>詳細內容</button></a></td>
                        <td>
                        <form action='volunteer_evaluation.php' method='post' onsubmit='submitForm(event)'>
                        <input type='hidden' name='user_id' value='{$user_id}'>
                        <input type='hidden' name='username' value='{$username}'>
                        <input type='hidden' name='activity_id' value='{$activity_id}'>
                        <button  type='submit' name='share' class='okbtn'>加入</button>
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