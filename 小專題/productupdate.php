<?php
  require_once('conn.php');
  $sql = "SELECT * FROM `product` WHERE `pId` = '{$_POST['id']}' ";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  
    if(isset($_POST['pprice'])){
      $sql1 = "UPDATE `product` SET `pPrice`='{$_POST['pprice']}' WHERE `pId` = '{$row['pId']}'";
      $res1 = mysqli_query($conn, $sql1);
      echo $sql1;
      if ($res1) {
        header("Location:product.php");
      } else {
          echo "更新失败";
      }
    }

?>