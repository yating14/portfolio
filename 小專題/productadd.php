<?php
  include("conn.php");
  mysqli_select_db($conn, "0607");

  if (!empty($_POST["pid"]) && !empty($_POST["pname"]) && !empty($_POST["pformat"]) && !empty($_POST["pprice"]) && !empty($_POST["prack"]) && isset($_POST["isbundle"])) {
    if ($_POST["isbundle"] == "1") {
      $sql = "INSERT INTO `product`(`pId`, `pName`, `pFormat`, `pPrice`, `pRack`, `isBundle`) VALUES ('{$_POST['pid']}', '{$_POST['pname']}', '{$_POST['pformat']}', '{$_POST['pprice']}', '{$_POST['prack']}', '{$_POST['isbundle']}')";  
      mysqli_query($conn, $sql);
    } else {
      if (!empty($_POST["bid"])) {
        $sql = "INSERT INTO `product`(`pId`, `pName`, `pFormat`, `pPrice`, `pRack`, `isBundle`, `bId`) VALUES ('{$_POST['pid']}', '{$_POST['pname']}', '{$_POST['pformat']}', '{$_POST['pprice']}', '{$_POST['prack']}', '{$_POST['isbundle']}', '{$_POST['bid']}')";  
        mysqli_query($conn, $sql);
      } else {
        $sql = "INSERT INTO `product`(`pId`, `pName`, `pFormat`, `pPrice`, `pRack`, `isBundle`) VALUES ('{$_POST['pid']}', '{$_POST['pname']}', '{$_POST['pformat']}', '{$_POST['pprice']}', '{$_POST['prack']}', '{$_POST['isbundle']}')";  
        mysqli_query($conn, $sql);
      }
    }
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
        商品編號：<input type="text" name="pid"><br>
        商品名稱：<input type="text" name="pname"><br>
        規格：<input type="text" name="pformat"><br>
        售價：<input type="number" name="pprice"><br>
        儲放位置：<input type="text" name="prack"><br>
        是否為組合商品：<input type="radio" name="isbundle" value="1">是
        <input type="radio" name="isbundle" value="0">否<br>
        所屬的組合編號：
        <select name="bid">
          <?php
            $sql1 = "SELECT * FROM product WHERE isBundle=1";
            $res1 = mysqli_query($conn, $sql1);
            echo "<option value=''>無</option>";
            while($row1 = mysqli_fetch_assoc($res1)) {
              echo "<option value='{$row1['pId']}'>{$row1['pName']}</option>";
            }
          ?>
        </select><br><br>
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
        } else {
          echo '<script>alert("新增失敗！請輸入完整的資料");</script>';
          // echo "更新失敗, 或者您輸入的資料與原本相同";
        }
      }
      
      mysqli_close($conn);
    ?>
  <br><a href="product.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>
</body>
</html>