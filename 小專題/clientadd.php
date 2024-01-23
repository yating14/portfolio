<?php
  include("conn.php");
  mysqli_select_db($conn, "0607");

  if(!empty($_POST["cid"]) && !empty($_POST["cname"]) && !empty($_POST["ccontact"]) && !empty($_POST["ctel"]) && !empty($_POST["cadd"])) {
    $sql = "INSERT INTO client (`cId`, `cName`, `cContact`, `cTel`, `cAddress`) VALUES ('{$_POST["cid"]}','{$_POST["cname"]}','{$_POST["ccontact"]}','{$_POST["ctel"]}','{$_POST["cadd"]}')";  
    //為了方便debug，可以將串起來的sql語法印出來確認
    // echo "$sql<br>";
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
        客戶編號：<input type="text" name="cid" ><br>
        客戶名稱：<input type="text" name="cname" ><br>
        聯絡人：<input type="text" name="ccontact"><br>
        聯絡電話：<input type="text" name="ctel"><br>
        地址：<input type="text" name="cadd"><br>
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
  <br><a href="client.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>

</body>
</html>