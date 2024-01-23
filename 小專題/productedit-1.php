<?php
    require_once('conn.php');
    if (!empty($_GET['edit'])){
      $sql = "SELECT * FROM `product` WHERE `pId` = '{$_GET['edit']}' ";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_array($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <form method="POST" action="productupdate.php">
    商品編號：<?=$row['pId']?><br>
    商品名稱：<?=$row['pName']?><br>
    售價：<input name="pprice" type="text" value="<?=$row['pPrice'];?>"><br>

    <input name="id" type="hidden" value="<?=$row['pId'];?>">
    <button type="submit" class="btn" onclick="showAlert()">確認 <i class="fa-regular fa-square-check"></i></button>
  </form>
  <br>
  <a href="productedit.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>
  
</body>
</html>