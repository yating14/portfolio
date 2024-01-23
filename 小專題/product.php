<?php
    require_once('conn.php');
    $sql = "SELECT * FROM `product`;";
    $res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="form">
        <form action="product.php" method="$_GET">
            商品編號：<input class="search" type="text" name="pid" placeholder="請輸入編號">
            
            商品名稱：<input class="search" type="text" name="pname" placeholder="請輸入名稱">
            <button type="submit" name="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <hr>
    <div class="right">
        <button onclick="location.href='productadd.php'" class="btn">新增 <i class="fa-regular fa-square-plus"></i></i></button>
        <button onclick="location.href='productedit.php'" class="btn">編輯 <i class="fa-regular fa-pen-to-square"></i></i></button>
        <!-- <button onclick="location.href='productdel.php'" class="btn">刪除 <i class="fa-regular fa-trash-can"></i></i></button> -->
    </div>
    <table border="1">
        <tr>   
            <td>商品編號</td>
            <td>商品名稱</td>
            <td>規格</td>
            <td>售價</td>
            <td>儲放位置</td>
        </tr>
        <tr>
        <?php
            if(!empty($_GET['pid'])){
                $select=$_GET['pid'];
                $sql = "SELECT * FROM `product` WHERE `pId` like '%$select%';";  
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['pId']}</td>
                        <td>{$row['pName']}</td>
                        <td>{$row['pFormat']}</td>
                        <td>{$row['pPrice']}</td>
                        <td>{$row['pRack']}</td>";
                }
            }
            elseif(!empty($_GET['pname'])){
                    $select=$_GET['pname'];
                    $sql = "SELECT * FROM `product` WHERE `pName` like '%$select%';";                          
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>
                        <td>{$row['pId']}</td>
                        <td>{$row['pName']}</td>
                        <td>{$row['pFormat']}</td>
                        <td>{$row['pPrice']}</td>
                        <td>{$row['pRack']}</td>";
                }
            }
            else{
                $sql = "SELECT * FROM `product`;";
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['pId']}</td>
                        <td>{$row['pName']}</td>
                        <td>{$row['pFormat']}</td>
                        <td>{$row['pPrice']}</td>
                        <td>{$row['pRack']}</td>";
                    }
                }
                mysqli_close($conn);
            ?>
        </tr>
    </table>
    
</body>
</html>