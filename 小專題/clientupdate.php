<?php
  require_once('conn.php');
  $sql = "SELECT * FROM `client` WHERE `cId` = '{$_POST['id']}' ";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  
    if(isset($_POST['cname'])){
        $sql1 = "UPDATE `client` SET `cName`='{$_POST['cname']}' WHERE `cId` = '{$row['cId']}'";
        $res1 = mysqli_query($conn, $sql1);
        echo $sql1;
        if ($res1) {
          header("Location:client.php");
        } else {
            echo "更新失败";
        }
    }
    if(isset($_POST['ccontact'])){
        $sql1 = "UPDATE `client` SET `cContact`='{$_POST['ccontact']}' WHERE `cId` = '{$row['cId']}'";
        $res1 = mysqli_query($conn, $sql1);
        echo $sql1;
        if ($res1) {
          header("Location:client.php");
        } else {
            echo "更新失败";
        }
    }
    if(isset($_POST['ctel'])){
        $sql1 = "UPDATE `client` SET `cTel`='{$_POST['ctel']}' WHERE `cId` = '{$row['cId']}'";
        $res1 = mysqli_query($conn, $sql1);
        echo $sql1;
        if ($res1) {
          header("Location:client.php");
        } else {
            echo "更新失败";
        }
    }
    if(isset($_POST['cadd'])){
        $sql1 = "UPDATE `client` SET `cAddress`='{$_POST['cadd']}' WHERE `cId` = '{$row['cId']}'";
        $res1 = mysqli_query($conn, $sql1);
        echo $sql1;
        if ($res1) {
          header("Location:client.php");
        } else {
            echo "更新失败";
        }
    }
?>