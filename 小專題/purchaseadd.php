<?php
  include("conn.php");
  mysqli_select_db($conn, "0607");

  if(!empty($_POST["pdate"]) && !empty($_POST["pid"]) && !empty($_POST["pquantity"])) {
    $pno=$conn->insert_id;
    $sql = "INSERT INTO purchase (`pNo`,`pDate`, `pId`, `pQuantity`) VALUES ('$pno','{$_POST["pdate"]}','{$_POST["pid"]}','{$_POST["pquantity"]}')";  
    mysqli_query($conn, $sql);
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
        進貨日期：<input type="date" name="pdate"><br>
        商品編號：<input type="text" name="pid" ><br>
        進貨數量：<input type="number" name="pquantity"><br>
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
        $sql1 = "SELECT * FROM `stock` WHERE `pId` = '{$_POST["pid"]}';";
        $res1 = mysqli_query($conn, $sql1);
        $row1 = mysqli_fetch_assoc($res1);
        $squantity = $row1['sQuantity'];//原本的庫存
        $sadvance = $row1['sAdvance'];//預扣的數量
        $pquantity = $_POST["pquantity"];//進貨的數量
        $stock = $squantity+$pquantity;//總共的庫存
        $advance=$sadvance+$pquantity;//加上進貨數量的預扣
        $sql2 = "UPDATE `stock` SET `sQuantity`=$stock, `sAdvance`='$advance' WHERE `pId` = '{$_POST["pid"]}'";
        $res2 = mysqli_query($conn, $sql2);
        // echo $sql2;
      } else {
        echo '<script>alert("新增失敗！請輸入完整的資料");</script>';
        // echo "更新失敗, 或者您輸入的資料與原本相同";
      }
    }
    
    mysqli_close($conn);
  ?>
  <br><a href="purchase.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>

</body>
</html>

