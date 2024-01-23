<?php
    require_once('conn.php');
    $sql = "SELECT * FROM `purchase`;";
    $res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>進貨單</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="form">
        <form action="purchase.php" method="$_GET">
            庫架編號：<input class="search" type="text" name="srack" placeholder="請輸入編號">
            
            商品編號：<input class="search" type="text" name="pid" placeholder="請輸入編號">
            <button type="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <hr>
    <div class="right">
        <button onclick="location.href='purchaseadd.php'" class="btn">新增 <i class="fa-regular fa-square-plus"></i></button>
    </div>
    <table border="1">
        <tr>
            <td>進貨單號</td>
            <td>進貨日期</td>
            <td>庫架編號</td>  
            <td>商品編號</td> 
            <td>商品名稱</td> 
            <td>進貨數量</td>      
        </tr>
        <tr>
        <?php
            if(!empty($_GET['srack'])){
                $select=$_GET['srack'];
                $sql = "SELECT * FROM `purchase`,`product` WHERE purchase.pId = product.pId AND pRack LIKE '%$select%';;";  
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                    <td>{$row['pNo']}</td>
                    <td>{$row['pDate']}</td>
                    <td>{$row['pRack']}</td>
                    <td>{$row['pId']}</td>
                    <td>{$row['pName']}</td>
                    <td>{$row['pQuantity']}</td>";
               }               
            }
            elseif(!empty($_GET['pid'])){
                $select=$_GET['pid'];
                $sql = "SELECT * FROM `purchase`,`product` WHERE purchase.pId = product.pId AND product.pId LIKE '%$select%';";  
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                    <td>{$row['pNo']}</td>
                    <td>{$row['pDate']}</td>
                    <td>{$row['pRack']}</td>
                    <td>{$row['pId']}</td>
                    <td>{$row['pName']}</td>
                    <td>{$row['pQuantity']}</td>";
               }               
            }
            else{
                $sql = "SELECT * FROM `purchase`,`product` WHERE purchase.pId = product.pId;";
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                    <td>{$row['pNo']}</td>
                    <td>{$row['pDate']}</td>
                    <td>{$row['pRack']}</td>
                    <td>{$row['pId']}</td>
                    <td>{$row['pName']}</td>
                    <td>{$row['pQuantity']}</td>";
               }
            }
            mysqli_close($conn);
            ?>
        </tr>
    </table>
    
</body>
</html>