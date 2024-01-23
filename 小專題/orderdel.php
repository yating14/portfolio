<?php
  require_once('conn.php');
  ?>

<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    
  <?php 
    $sql = "SELECT * FROM `order` NATURAL JOIN `details`;";
    $result = mysqli_query($conn, $sql);

    //如果已有記錄, 使用迴圈顯示所有資料
    if (mysqli_num_rows($result) >0){
      echo "<hr><table border='1'>";
      echo "<tr>
              <td>訂單編號</td>
              <td>訂購日期</td>
              <td>客戶編號</td>
              <td>商品編號</td>
              <td>訂購數量</td>
            </tr>";
      while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
              <td>{$row['oNo']}</td>
              <td>{$row['oDate']}</td>
              <td>{$row['cId']}</td>
              <td>{$row['oQuantity']}</td>
              <td>{$row['pId']}</td>";
        echo "<td><a href='orderdel.php?oNo={$row['oNo']}'>刪除</a></td>";  // 刪除(DELETE)資料觸發
        //echo "<td><a href='ch03_5_edit.php?edit={$row['oNo']}'>編輯</a></td>";  // 編輯(UPDATE)資料觸發
        echo "</tr>";
      }
      echo '</table>';
    }
    
    if (isset($_GET['oNo'])){
      //將 del 參數所指定的編號的記錄刪除
      $sql = "DELETE FROM `details` WHERE oNo = {$_GET['oNo']};
              DELETE FROM `order` WHERE oNo = {$_GET['oNo']}";
      mysqli_multi_query($conn, $sql);
      //取得被刪除的記錄筆數
      $rowDeleted = mysqli_affected_rows($conn);
      //如果刪除的筆數大於 0, 則顯示成功, 若否, 便顯示失敗
      if ($rowDeleted>=1){
        echo "刪除成功<br>";
      } else {
        echo "刪除失敗<br>";
      }
    }
    

    mysqli_close($conn);
  ?>
  
  <a href="order.php">返回上一頁</a>
  
</body>
</html>