<?php
  $server_name = 'localhost';
  $username = 'root';
  $password = '';
  $db_name = '0607';

  $conn = new mysqli($server_name, $username, $password, $db_name);

  if (mysqli_connect_errno()) {
    die('無法連線資料庫伺服器：' . $conn->connect_error);
  }
  else
  // echo '成功連結資料庫伺服器<br>';

  mysqli_set_charset($conn, "utf8");

  $selectedDB = @mysqli_select_db($conn, "0607");
  if (!$selectedDB) 
    die("指定資料庫失敗");
  else
    // echo "指定資料庫成功<br>";
?>