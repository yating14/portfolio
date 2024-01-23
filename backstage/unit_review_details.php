<?php
    require_once('conn.php');
    $unit_id = $_GET['unit_id'];

    $sql = "SELECT * FROM `unit`
    INNER JOIN `unit_info` ON unit.unit_id = unit_info.unit_id
    INNER JOIN `unit_photo` ON unit.unit_id = unit_photo.unit_id
    WHERE unit.unit_id = '{$unit_id}'";
    $res = mysqli_query($conn, $sql);

    $rows = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = $row;
    }

    if (isset($_POST['submit'])) {
        $unit_id = $_POST['unit_id'];
    
        $sql = "UPDATE `unit` SET `review` = 1 WHERE `unit_id` = '{$unit_id}'";
        $res = mysqli_query($conn, $sql);  
    
        if ($res) {
            echo '<script>alert("核可成功"); window.location.href = "index.html#action=open_unit_review";</script>';
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
    <h3><a href="#" onclick="loadContent('unit_review.php');" class="pgup"><i class="fa-solid fa-reply"></i>回待審核列表</a></h3>
    <h1>基本資料</h1>
    <div class="showdetails-1">
        <table border="1" cellpadding="10">
            <tbody>
                <?php
                    foreach ($rows as $row) {
                        $base64Data = base64_encode($row['image']);
                        $imageSrc = "data:image/jpeg;base64," . $base64Data;

                        echo "<tr>
                                <td>單位照片</td>
                                <td><img src='$imageSrc' alt='Profile Picture'></td>
                            </tr>
                            <tr>
                                <td>單位名稱</td>
                                <td>{$row['unitname']}</td>
                            </tr>
                            <tr>
                                <td>負責人</td>
                                <td>{$row['pic_name']}</td>
                            </tr>
                            <tr>
                                <td>聯絡信箱</td>
                                <td>{$row['account']}</td>
                            </tr>
                            <tr>
                                <td>連絡電話</td>
                                <td>{$row['register_phone']}</td>
                            </tr>
                            <tr>
                                <td>連絡時間</td>
                                <td>{$row['contact_time']}</td>
                            </tr>
                            <tr>
                                <td>郵遞區號</td>
                                <td>{$row['postal_code']}</td>
                            </tr>
                            <tr>
                                <td>縣市</td>
                                <td>{$row['county_city']}</td>
                            </tr>
                            <tr>
                                <td>行政區</td>
                                <td>{$row['district']}</td>
                            </tr>
                            <tr>
                                <td>地址</td>
                                <td>{$row['address']}</td>
                            </tr>
                            <tr>
                                <td>簡介</td>
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
    <form action="unit_review_details.php" method="post" onsubmit="submitForm(event)">
        <input type="hidden" name="unit_id" value="<?php echo $unit_id; ?>">
        <button type="submit" name="submit" class="okbtn">核可</button>
        <button type="button" class="btn">拒絕</button>
    </form>
    </div>
</body>
</html> 