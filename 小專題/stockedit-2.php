<?php
  require_once('conn.php');
  $sql = "SELECT * FROM `stock` WHERE `pId` = '{$_POST['id']}' ";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  
    if(isset($_POST['ssa'])){
      $sql1 = "UPDATE `stock` SET `sSafety`='{$_POST['ssa']}' WHERE `pId` = '{$row['pId']}'";
      $res1 = mysqli_query($conn, $sql1);
      echo $sql1;
      if ($res1) {
        header("Location:stock.php");
      } else {
          echo "更新失败";
      }
    }
?>