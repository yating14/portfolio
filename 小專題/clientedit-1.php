<?php
    require_once('conn.php');
    if (!empty($_GET['edit'])){
      $sql = "SELECT * FROM `client` WHERE `cId` = '{$_GET['edit']}' ";
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
    <form method="POST" action="clientupdate.php">
      客戶名稱：<input name="cname" type="text" value="<?=$row['cName'];?>"><br>
      聯絡人：<input name="ccontact" type="text" value="<?=$row['cContact'];?>"><br>
      連絡電話：<input name="ctel" type="text" value="<?=$row['cTel'];?>"><br>
      地址：<input name="cadd" type="text" value="<?=$row['cAddress'];?>"><br>

      <input name="id" type="hidden" value="<?=$row['cId'];?>">
      
      <button type="submit" name="submit" class="btn">更改 <i class="fa-regular fa-square-check"></i></button>
  </form>

  <br><a href="clientedit.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>
  
</body>
</html>