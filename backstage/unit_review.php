<?php
require_once('conn.php');
$sql = "SELECT * FROM `unit` WHERE review='0';";
$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res); //總共有幾筆資料
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
    <h1>待審核列表</h1>
    <hr>
    <h2>共<?php echo $count ?>筆</h2>
    <div class="showdata">
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>#</th>
                    <th>單位名稱</th>
                    <th>編號</th>
                    <th>註冊時間</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $unit_id = $row['unit_id'];
                    echo "<tr>
                        <td>{$cnt}</td>
                        <td>{$row['unitname']}</td>
                        <td>{$row['unit_id']}</td>
                        <td>{$row['created_time']}</td>
                        <td><a href='#' onclick=loadContent('unit_review_details.php?unit_id={$unit_id}')><button class='btn'>詳細內容</button></a></td>";
                    $cnt++;
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>