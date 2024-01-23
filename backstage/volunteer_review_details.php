<?php
function validateTaiwanID($idNumber)
{
    // 身分證號碼正則表達式，台灣身分證號碼的格式
    $pattern = '/^[A-Z][12][0-9]{8}$/';

    if (preg_match($pattern, $idNumber)) {
        // 檢查英文字母部分是否符合規則
        $firstLetter = ord($idNumber[0]);  // 取得第一個字母的ASCII碼
        if ($firstLetter >= 65 && $firstLetter <= 89) {  // A-Z對應的ASCII範圍
            // 身分證號碼合法
            return true;
        }
    }

    // 身分證號碼不合法
    return false;
}

require_once('conn.php');
$user_id = $_GET['user_id'];
echo "User ID from URL: " . $user_id; // 调试输出

$sql = "SELECT * FROM `users`
            INNER JOIN `users_info` ON users.user_id = users_info.user_id
            INNER JOIN `users_photo` ON users.user_id = users_photo.user_id
            WHERE users.user_id = '{$user_id}'";
$res = mysqli_query($conn, $sql);

$rows = [];
while ($row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
}

if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];

    $sql = "UPDATE `users` SET `review` = 1 WHERE `user_id` = '{$user_id}'";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo '<script>alert("核可成功"); window.location.href = "index.html#action=open_volunteer_review";</script>';
    }
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
    <h3><a href="#" onclick="loadContent('volunteer_review.php');" class="pgup"><i class="fa-solid fa-reply"></i>回待審核列表</a></h3>
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
                    <td> {$row['identity']}";
                    $idNumber = "{$row['identity']}";
                    if (validateTaiwanID($idNumber)) {
                        echo "<span style='color:green'> (此身分證號碼為合法)</span>";
                    } else {
                        echo "<span style='color:red'> (此身分證號碼為不合法)</span>";
                    }
                    echo "</td>
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
                </tr>";
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
    <br><br>
    <div class="check">
        <form action="volunteer_review_details.php" method="post" onsubmit="submitForm(event)">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <button type="submit" name="submit" class="okbtn">核可</button>
            <button type="button" class="btn">拒絕</button>
        </form>
    </div>
</body>

</html>