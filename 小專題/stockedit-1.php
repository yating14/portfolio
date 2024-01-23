<?php
    require_once('conn.php');
    if (!empty($_GET['edit'])){
      $sql = "SELECT * FROM `stock` WHERE `pId` = '{$_GET['edit']}' ";
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
    <form method="POST" action="stockupdate.php">
    商品編號：<?=$row['pId']?><br>

    安全數量：<input name="ssa" type="number" value="<?=$row['sSafety'];?>">
    <input name="id" type="hidden" value="<?=$row['pId'];?>">
    <button type="submit" class="btn">確認 <i class="fa-regular fa-square-check"></i></button>
  </form>
  <a href="stockedit.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>
  
</body>
</html>