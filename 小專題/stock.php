<?php
    require_once('conn.php');
    $sql = "INSERT INTO stock (pId)
        SELECT p.pId FROM product p
        LEFT JOIN stock s ON p.pId = s.pId
        WHERE s.pId IS NULL";
    $res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>庫存</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="form">
        <form action="stock.php" method="$_GET">
            庫架編號：<input class="search" type="text" name="srack" placeholder="請輸入編號">

            商品編號：<input class="search" type="text" name="pid" placeholder="請輸入編號">
            <button type="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <hr>
    <div class="right">
        <!-- <button onclick="location.href='stockadd.php'" class="btn">新增 <i class="fa-regular fa-square-plus"></i></button> -->
        <button onclick="location.href='stockedit.php'" class="btn">編輯 <i class="fa-regular fa-pen-to-square"></i></button> 
    </div>
    <table border="1">
        <tr>
            <td>庫架編號</td>
            <td>商品編號</td>
            <td>商品名稱</td>
            <td>庫存數量</td>
            <td>預扣數量</td>
            <td>安全數量</td>    
        </tr>
        <tr>
        <?php
            if (!empty($_GET['srack'])) {
                $select = $_GET['srack'];
                $sql = "SELECT * FROM `stock`,`product` WHERE stock.pId = product.pId AND pRack LIKE '%$select%'";
                $res = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($res)) {
                  echo "<tr>
                          <td>{$row['pRack']}</td>
                          <td>{$row['pId']}</td>
                          <td>{$row['pName']}</td>
                          <td>{$row['sQuantity']}</td>
                          <td>{$row['sAdvance']}</td>
                          <td>{$row['sSafety']}</td>";
                }
            }
            elseif(!empty($_GET['pid'])){
                $select=$_GET['pid'];
                $sql = "SELECT * FROM `stock`,`product` WHERE stock.pId = product.pId AND stock.pId LIKE '%$select%'";
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['pRack']}</td>
                        <td>{$row['pId']}</td>
                        <td>{$row['pName']}</td>
                        <td>{$row['sQuantity']}</td>
                        <td>{$row['sAdvance']}</td>
                        <td>{$row['sSafety']}</td>";
                }  
            }
            else{
                $sql = "SELECT * FROM `product`,`stock` WHERE stock.pId = product.pId;";
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['pRack']}</td>
                        <td>{$row['pId']}</td>
                        <td>{$row['pName']}</td>
                        <td>{$row['sQuantity']}</td>
                        <td>{$row['sAdvance']}</td>
                        <td>{$row['sSafety']}</td>";
               }
            }
            mysqli_close($conn);
            ?>
        </tr>
    </table>
    
</body>
</html>