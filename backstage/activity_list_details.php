<?php
require_once('conn.php');
$activity_id = $_GET['activity_id'];
if (!empty($_GET['volunteers_share'])) {
    $volunteers_share = $_GET['volunteers_share'];
} else {
    $volunteers_share = 0;
}
$sql = "SELECT activity.*, unit.*, activity_photo.type AS photo_type, activity_photo.image AS photo_image
FROM activity
JOIN unit ON activity.unit_id = unit.unit_id
LEFT JOIN activity_photo ON activity.activity_id = activity_photo.activity_id
WHERE activity.activity_id = '{$activity_id}';";

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

<body>
    <?php
    if ($volunteers_share == 1) {
        echo '<h3><a href="#" onclick="loadContent(\'volunteer_evaluation.php\');" class="pgup"><i class="fa-solid fa-reply"></i>回志工評價</a></h3>';
    } else {
        echo '<h3><a href="#" onclick="loadContent(\'activity_list.php\');" class="pgup"><i class="fa-solid fa-reply"></i>回活動列表</a></h3>';
    }
    ?>
    <h1>基本資料</h1>
    <div class="showdetails-1">
        <table border="1" cellpadding="10">
            <tbody>
                <?php
                foreach ($rows as $row) {
                    echo "<tr>
                                <td>單位名稱</td>
                                <td colspan=3>{$row['unitname']}</td>
                            </tr>
                            <tr>
                                <td>聯絡人</td>
                                <td>{$row['contact_person']}</td>
                                <td>連絡電話</td>
                                <td>{$row['phone']}</td>
                            </tr>
                         ";
                }
                ?>
            </tbody>
        </table>
    </div>
    <br>
    <div class="showdetails-2">
        <table border="1" cellpadding="10">
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $base64Data = base64_encode($row['photo_image']);
                    $imageSrc = "data:image/jpeg;base64," . $base64Data;

                    echo "
                            <tr>
                                <td>活動名稱</td>
                                <td>{$row['event_name']}</td>
                                <td>活動種類</td>
                                <td>{$row['type']}</td>
                            </tr>
                            <tr>
                                <td>活動日期</td>
                                <td colspan=3>{$row['event_date']}</td>
                            </tr>
                            <tr>
                                <td>需求人數</td>
                                <td>{$row['demand']}</td>
                                <td>服務時數</td>
                                <td>{$row['hours']}</td>
                            </tr>
                            <tr>
                                <td>活動地點</td>
                                <td colspan=3>{$row['place']}</td>
                            </tr>
                            <tr>
                                <td>活動簡介</td>
                                <td colspan=3>{$row['event_Introduction']}</td>
                            </tr>
                            <tr>
                                <td>服務內容</td>
                                <td colspan=3>{$row['service_content']}</td>
                            </tr>
                            <tr>
                                <td>活動宣傳照片</td>
                                <td colspan=3><img src='$imageSrc' alt='Profile Picture'></td>
                            </tr>
                         ";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>