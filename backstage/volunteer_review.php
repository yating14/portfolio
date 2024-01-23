<?php
    require_once('conn.php');
    $sql = "SELECT * FROM `users` WHERE review='2';";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res); //總共有幾筆資料
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
    <h1>黑名單</h1><hr>
    <h2>共<?php echo $count?>筆</h2>
    <div class="showdata">
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>#</th>
                    <th>姓名</th>
                    <th>編號</th>
                    <th>封鎖原因</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $cnt = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $user_id = $row['user_id'];
                    echo "<tr>
                        <td>{$cnt}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['created_time']}</td>";
                        
                    $cnt++;
                }
                mysqli_close($conn);
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>