<?php
  include("conn.php");
  mysqli_select_db($conn, "0607");
  if (!empty($_POST["odate"]) && !empty($_POST["cid"] && !empty($_POST["oquantity"]) && !empty($_POST["pid"])))  {
    $sql1 = "INSERT INTO `orders`( `oDate`, `cId`) VALUES ('{$_POST["odate"]}','{$_POST["cid"]}')";
    // echo "$sql1<br>";
    mysqli_query($conn, $sql1);
    $ono=$conn->insert_id;
    $sql2 = "INSERT INTO `details` (`oNo`, `oQuantity`, `pId`) VALUES ('$ono', '{$_POST["oquantity"]}', '{$_POST["pid"]}')";
    // echo "$sql2<br>";
    mysqli_query($conn, $sql2);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
        訂購日期：<input type="date" name="odate"><br>
        客戶編號：<input type="text" name="cid"><br>
        商品編號：<input type="text" name="pid"><br>
        訂購數量：<input type="number" name="oquantity"><br>       
        <button type="submit" name="submit" class="btn">確認 <i class="fa-regular fa-square-check"></i></button>
    </form>
  <?php
    //取得被更新的記錄筆數
    $rowUpdated = mysqli_affected_rows($conn);
    //如果更新的筆數大於 0, 則顯示成功, 若否, 便顯示失敗
    if(isset($_POST['submit'])){
      if ($rowUpdated >=1){
        echo '<script>alert("新增成功！");</script>';
        // echo "資料更新成功";
        $sql3="SELECT * FROM `orders` NATURAL JOIN `details`;";
        $res3 = mysqli_query($conn, $sql3);
        while ($row3 = mysqli_fetch_assoc($res3))
          $pId = $row3['pId'];//訂單裡的商品編號
          $oquantity1 = $_POST["oquantity"];//訂購數量
          $sqlbid="SELECT * FROM product WHERE pId='$pId' AND isBundle=1";//找商品編號是組合的
          $resbid=mysqli_query($conn, $sqlbid);
          if(mysqli_num_rows($resbid)>0){
            $row= mysqli_fetch_assoc($resbid);
            $bid=$row['bId'];
            $sqlupdate = "UPDATE stock 
            JOIN product ON stock.pId = product.pId 
            SET stock.sAdvance = stock.sAdvance -  $oquantity1
            WHERE product.bId = '$pId'";
            mysqli_query($conn, $sqlupdate);
          }
          else{
            $sql4 = "SELECT * FROM `stock` WHERE `pId` = '$pId';";//找庫存對應的商品編號
            $res4 = mysqli_query($conn, $sql4);
            $row4 = mysqli_fetch_assoc($res4);
            $sAdvance = $row4['sAdvance'];//預扣數量
            $oquantity = $_POST["oquantity"];//訂購數量
            $remaining = $sAdvance-$oquantity;//預扣剩餘的數量
            $sql5 = "UPDATE `stock` SET `sAdvance`='$remaining' WHERE `pId` = '$pId'";
            $res5 = mysqli_query($conn, $sql5);
          }
      } 
      else {
        echo '<script>alert("新增失敗！請輸入完整的資料");</script>';
        // echo "更新失敗, 或者您輸入的資料與原本相同";
      }
    }
    mysqli_close($conn);
  ?>
  <br><a href="order.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>

</body>
</html>