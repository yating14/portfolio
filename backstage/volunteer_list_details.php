<?php
require_once('conn.php');
$user_id = $_GET['user_id'];
if (!empty($_GET['volunteers_share'])) {
    $volunteers_share = $_GET['volunteers_share'];
} else {
    $volunteers_share = 0;
}
$sql = "SELECT * FROM `users`
            INNER JOIN `users_info` ON users.user_id = users_info.user_id
            INNER JOIN `users_photo` ON users.user_id = users_photo.user_id
            WHERE users.user_id = '{$user_id}'";
$res = mysqli_query($conn, $sql);

$rows = [];
while ($row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
}

mysqli_close($conn);
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
<style>
    /* Style for the Add to Blacklist Button */
    .blacklist-button {
        background-color: #ff0000;
        /* Red background color */
        color: #fff;
        /* White text color */
        padding: 10px 20px;
        /* Padding around the button text */
        border: none;
        /* Remove button border */
        border-radius: 5px;
        /* Rounded corners */
        cursor: pointer;
        /* Cursor style on hover */
    }

    .blacklist-button:hover {
        background-color: #cc0000;
        /* Darker red on hover */
    }
</style>

<body>
    <?php
    if ($volunteers_share == 1) {
        echo '<h3><a href="#" onclick="loadContent(\'volunteer_evaluation.php\');" class="pgup"><i class="fa-solid fa-reply"></i>回志工評價</a></h3>';
    } else {
        echo '<h3><a href="#" onclick="loadContent(\'volunteer_list.php\');" class="pgup"><i class="fa-solid fa-reply"></i>回志工列表</a></h3>';
    }
    ?>
    <h1>基本資料</h1>
    <div class="showdetails-1">
        <table border="1" cellpadding="10">
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $base64Data = base64_encode($row['image']);
                    $imageSrc = "data:image/jpeg;base64," . $base64Data;

                    echo "<tr>
                                <td>志工大頭貼</td>
                                <td><img src='$imageSrc' alt='Profile Picture'></td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td>{$row['username']}</td>
                            </tr>
                            <tr>
                                <td>身分證字號</td>
                                <td>{$row['identity']}</td>
                            </tr>
                            <tr>
                                <td>生日</td>
                                <td>{$row['birthday']}</td>
                            </tr>
                            <tr>
                                <td>性別</td>
                                <td>{$row['sex']}</td>
                            </tr>
                            <tr>
                                <td>連絡電話</td>
                                <td>{$row['phone']}</td>
                            </tr>
                            <tr>
                                <td>電子郵件</td>
                                <td>{$row['account']}</td>
                            </tr>
                            <tr>
                                <td>就讀學校</td>
                                <td>{$row['school']}</td>
                            </tr>
                            <tr>
                                <td>教育程度</td>
                                <td>{$row['education']}</td>
                            </tr>
                            ";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="showdetails-2">
        <h2>志工詳細資料</h2>
        <table border="1" cellpadding="10">
            <tbody>
                <?php
                foreach ($rows as $row) {
                    echo "<tr>
                            <td>可服務時間</td>
                            <td>{$row['servicetimes']}</td>
                        </tr>
                        <tr>
                            <td>興趣</td>
                            <td>{$row['interest']}</td>
                        </tr>
                        <tr>
                            <td>專長</td>
                            <td>{$row['specialty']}</td>
                        </tr>
                        <tr>
                            <td>自我介紹</td>
                            <td>{$row['introduction']}</td>
                        </tr>
                        ";
                }
                ?>
            </tbody>
        </table>
    </div>
    <br>
    <?php
    if ($volunteers_share != 1) {
        echo '<form action="update_blacklist.php" method="post">
    <input type="hidden" name="user_id" value="' . $user_id . '">
    <label for="blacklist_reason">加入黑名單原因：</label>
    <input type="text" name="blacklist_reason" id="blacklist_reason" placeholder="請輸入原因">
    <button type="submit" class="blacklist-button">加入黑名單</button>
</form>';
    }
    ?>

    <script>
        const addReasonButton = document.getElementById("addReasonButton");

        addReasonButton.addEventListener("click", function() {
            const reason = prompt("請輸入原因：");

            if (reason !== null && reason.trim() !== "") {
                // 如果用戶輸入了原因，將原因添加到表單中的一個隱藏字段
                const form = document.querySelector("form");
                const reasonInput = document.createElement("input");
                reasonInput.type = "hidden";
                reasonInput.name = "blacklist_reason";
                reasonInput.value = reason;
                form.appendChild(reasonInput);

                // 提交表單
                form.submit();
            }
        });
    </script>
</body>

</html>