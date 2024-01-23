<?php
require_once('conn.php');

if (!empty($_GET['unitname'])) {
    $select = $_GET['unitname'];
    $sql = "SELECT * FROM `unit` WHERE `unitname` LIKE '%$select%';";
} elseif (!empty($_GET['startdate']) && !empty($_GET['enddate'])) {
    $select1 = $_GET['startdate'];
    $select2 = $_GET['enddate'];
    $sql = "SELECT * FROM `unit` WHERE `created_time` BETWEEN '$select1' AND '$select2';"; //betwwen開始包含但結束不包含所以如果要都搜尋就要用>=
} else {
    $sql = "SELECT * FROM `unit` WHERE review='1';";
}

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
    <h1>單位列表</h1>
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
                        <form action="unit_list.php" method="get" onsubmit="searchVolunteers(event)">
                            <p>單位名稱：<input class="search" type="text" name="unitname" placeholder="請輸入單位名稱"></p><br>
                            <p>註冊日期區間：<input type="date" name="startdate"> ~ <input type="date" name="enddate"></p><br>
                            <button type="submit" name="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>
    <h2>共<?php echo $count ?>筆</h2>
    <div class="showdata">
        <table border="1" cellpadding="10" style="text-align: center;">
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
                        <td><a href='#' onclick=loadContent('unit_list_details.php?unit_id={$unit_id}')><button class='btn'>詳細內容</button></a></td>";
                    $cnt++;
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>