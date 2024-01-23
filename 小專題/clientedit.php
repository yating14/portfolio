<?php
    require_once('conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
  <?php 
    $sql = "SELECT * FROM `client`";
    $result = mysqli_query($conn, $sql);

    //如果已有記錄, 使用迴圈顯示所有資料
    if (mysqli_num_rows($result) >0){
      echo "<table border='1'>";
      echo "<tr>
            <td>客戶編號</td>
            <td>客戶名稱</td>
            <td>連絡人</td>
            <td>聯絡電話</td>
            <td>地址</td>
            <td></td>
            </tr>";
      while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
              <td>{$row['cId']}</td>
              <td>{$row['cName']}</td>
              <td>{$row['cContact']}</td>
              <td>{$row['cTel']}</td>
              <td>{$row['cAddress']}</td>";
              echo "<td><a href='clientedit-1.php?edit={$row['cId']}'>編輯</a></td>";  // 編輯(UPDATE)資料觸發
        echo "</tr>";
      }
      echo '</table>';
    }
    mysqli_close($conn);
  ?>
  <br>
  <a href="client.php"><i class="fa-solid fa-reply"></i> 返回上一頁</a>
  
</body>
</html>