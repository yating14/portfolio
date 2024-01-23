<?php
    require_once('conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>訂單</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="form">
        <form action="ship.php" method="$_GET">
            訂單日期：<input class="date" type="date" name="date" >

            訂單編號：<input class="search" type="text" name="ono" size="8" placeholder="請輸入編號">

            客戶編號：<input class="search" type="text" name="cid" size="8" placeholder="請輸入編號">

            商品編號：<input class="search" type="text" name="pno" size="8" placeholder="請輸入編號">
            <button type="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <hr>

    <table border="1">
        <tr>
            <td>訂單編號</td>
            <td>訂單日期</td>
            <td>客戶編號</td>
            <td>訂購數量</td>
            <td>商品編號</td>
            <td>出貨時間</td>
        </tr>
        <tr>    
        <?php
            if(!empty($_GET['date'])){
                $select=$_GET['date'];
                $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `oDate` like '%$select%' AND `deducted` = 1 ;";
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    $sql1 = "SELECT * FROM `stock` WHERE `pId` = '{$row['pId']}';";
                    $res1 = mysqli_query($conn, $sql1);
                    $row1 = mysqli_fetch_assoc($res1);
                    echo "<tr>
                        <td>{$row['oNo']}</td>
                        <td>{$row['oDate']}</td>
                        <td>{$row['cId']}</td>
                        <td>{$row['oQuantity']}</td>
                        <td>{$row['pId']}</td>
                        <td>{$row['sDate']}</td>";
                }
            }
            elseif(!empty($_GET['ono'])){
                    $select=$_GET['ono'];
                    $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `oNo` like '%$select%' AND `deducted` = '1' ;";
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res)){
                        $sql1 = "SELECT * FROM `stock` WHERE `pId` = '{$row['pId']}';";
                        $res1 = mysqli_query($conn, $sql1);
                        $row1 = mysqli_fetch_assoc($res1);
                        echo "<tr>
                            <td>{$row['oNo']}</td>
                            <td>{$row['oDate']}</td>
                            <td>{$row['cId']}</td>
                            <td>{$row['oQuantity']}</td>
                            <td>{$row['pId']}</td>
                            <td>{$row['sDate']}</td>";
                    }
            }
            elseif(!empty($_GET['cid'])){
                    $select=$_GET['cid'];
                    $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `cId` like '%$select%' AND `deducted` = 1 ;";
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res)) {
                        $sql1 = "SELECT * FROM `stock` WHERE `pId` = '{$row['pId']}';";
                        $res1 = mysqli_query($conn, $sql1);
                        $row1 = mysqli_fetch_assoc($res1);
                        echo "<tr>
                        <td>{$row['oNo']}</td>
                        <td>{$row['oDate']}</td>
                        <td>{$row['cId']}</td>
                        <td>{$row['oQuantity']}</td>
                        <td>{$row['pId']}</td>
                        <td>{$row['sDate']}</td>";
                    }
            }
            elseif(!empty($_GET['pno'])){
                    $select=$_GET['pno'];
                    $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `pId` like '%$select%' AND `deducted` = 1 ;";
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res)) {
                        $sql1 = "SELECT * FROM `stock` WHERE `pId` = '{$row['pId']}';";
                        $res1 = mysqli_query($conn, $sql1);
                        $row1 = mysqli_fetch_assoc($res1);
                        echo "<tr>
                        <td>{$row['oNo']}</td>
                        <td>{$row['oDate']}</td>
                        <td>{$row['cId']}</td>
                        <td>{$row['oQuantity']}</td>
                        <td>{$row['pId']}</td>
                        <td>{$row['sDate']}</td>";
                    }               
            }
            else{
                    $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `deducted` = 1 ;";
                    $res = mysqli_query($conn, $sql);   
                    while($row = mysqli_fetch_assoc($res)) {
                        $sql1 = "SELECT * FROM `stock` WHERE `pId` = '{$row['pId']}';";
                        $res1 = mysqli_query($conn, $sql1);
                        $row1 = mysqli_fetch_assoc($res1);
                        echo "<tr>
                            <td>{$row['oNo']}</td>
                            <td>{$row['oDate']}</td>
                            <td>{$row['cId']}</td>
                            <td>{$row['oQuantity']}</td>
                            <td>{$row['pId']}</td>
                            <td>{$row['sDate']}</td>";
                    }
            }
            mysqli_close($conn);
        ?>
        </tr>
    </table>
</body>
</html>