<?php
    require_once('conn.php');
    $sql = "SELECT * FROM `client`;";
    $res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>客戶</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="form">
        <form action="client.php" method="$_GET">
            客戶名稱：<input class="search" type="text" name="ccom" placeholder="請輸入名稱" >

            地址：<input class="search" type="text" name="cadd" placeholder="請輸入縣市">

            <button type="submit" name="submit" class="btn" >搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    <hr>
    <div class="right">
        <button onclick="location.href='clientadd.php'" class="btn">新增 <i class="fa-regular fa-square-plus"></i></i></button>
        <button onclick="location.href='clientedit.php'" class="btn">編輯 <i class="fa-regular fa-pen-to-square"></i></i></button>
        <!-- <button onclick="location.href='clientdel.php'" class="btn">刪除 <i class="fa-regular fa-trash-can"></i></i></button>   -->
    </div>
    <table border="1">
        <tr>
            <td>客戶編號</td>
            <td>客戶名稱</td>
            <td>連絡人</td>
            <td>聯絡電話</td>
            <td>地址</td>   
        </tr>
        <tr>
        <?php
            if(!empty($_GET['ccom'])){
                $select=$_GET['ccom'];
                $sql = "SELECT * FROM `client` WHERE `cName` like '%$select%';";  
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                    <td>{$row['cId']}</td>
                    <td>{$row['cName']}</td>
                    <td>{$row['cContact']}</td>
                    <td>{$row['cTel']}</td>
                    <td>{$row['cAddress']}</td>";
                }    
            }
            elseif(!empty($_GET['cadd'])){
                $select=$_GET['cadd'];
                $sql = "SELECT * FROM `client` WHERE `cAddress` like '%$select%';";  
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                    <td>{$row['cId']}</td>
                    <td>{$row['cName']}</td>
                    <td>{$row['cContact']}</td>
                    <td>{$row['cTel']}</td>
                    <td>{$row['cAddress']}</td>";
                }
            }
            else{
                $sql = "SELECT * FROM `client`;";
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['cId']}</td>
                        <td>{$row['cName']}</td>
                        <td>{$row['cContact']}</td>
                        <td>{$row['cTel']}</td>
                        <td>{$row['cAddress']}</td>";
                }
            }
            mysqli_close($conn);
        ?>
        </tr>
    </table> 
</body>
</html>