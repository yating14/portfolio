<?php
require_once('conn.php');

if (!empty($_GET['unitname'])) {
    $select = $_GET['unitname'];
    $sql = "SELECT * FROM `activity`,`unit` WHERE activity.unit_id = unit.unit_id AND unit.unitname LIKE '%$select%';";
} else {
    $sql = "SELECT * FROM `activity`,`unit` WHERE activity.unit_id = unit.unit_id;";
}

$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res); // 總共有幾筆資料

if (isset($_POST['hide_button'])) {
    $activity_id = $_POST['activity_id'];
    $update_sql = "UPDATE `activity` SET `is_hidden` = 1 WHERE `activity_id` = '{$activity_id}'";
    $result = mysqli_query($conn, $update_sql);
    if ($result) {
        echo '<script>alert("隱藏成功"); window.location.href = "index.html#action=open_activity_list";</script>';
    } else {
        echo '<script>alert("隱藏失敗");</script>';
    }
}

if (isset($_POST['show_button'])) {
    $activity_id = $_POST['activity_id'];
    $update_sql = "UPDATE `activity` SET `is_hidden` = 0 WHERE `activity_id` = '{$activity_id}'";
    $result = mysqli_query($conn, $update_sql);
    if ($result) {
        echo '<script>alert("顯示成功"); window.location.href = "index.html#action=open_activity_list";</script>';
    } else {
        echo '<script>alert("顯示失敗");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活動列表</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script>
        function submitForm(event) {
            event.preventDefault(); // 阻止默认表单提交行为
            var form = event.target;
            var activity_id = form.querySelector('input[name="activity_id"]').value;
            var formData = new FormData(form);

            fetch('activity_list.php', { // 修改为正确的后端处理程序 URL
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Fetch successful. Data:', data);
                    if (data === 'success') {
                        alert('操作成功');
                        // 切换按钮的类和文本
                        var button = form.querySelector('button');
                        if (button.classList.contains('hide-button')) {
                            button.classList.remove('hide-button');
                            button.classList.add('show-button');
                            button.textContent = '顯示';
                        } else if (button.classList.contains('show-button')) {
                            button.classList.remove('show-button');
                            button.classList.add('hide-button');
                            button.textContent = '隱藏';
                        }
                        // 重新加载整个页面
                        location.reload();
                    } else {
                        alert('操作失敗');
                    }
                })


        }
    </script>
</head>

<body>
    <h1>活動列表</h1>
    <hr>
    <div class="searchbar">
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <td>篩選</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form action="activity_list.php" method="$_GET" onsubmit="searchVolunteers(event)">
                            <p>單位名稱：<input class="search" type="text" name="unitname" placeholder="請輸入單位名稱"></p><br>
                            <button type="submit" name="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <h2>共<?php echo $count ?>筆</h2>
    <div class="showdata">
        <table border="1" cellpadding="10" style="text-align: center;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>活動編號</th>
                    <th>單位名稱</th>
                    <th>活動名稱</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $activity_id = $row['activity_id'];
                    echo "<tr>
                    <td>{$cnt}</td>
                    <td>{$row['activity_id']}</td>
                    <td>{$row['unitname']}</td>
                    <td>{$row['event_name']}</td>
                    <td><a href='#' onclick=loadContent('activity_list_details.php?activity_id={$activity_id}')><button class='btn'>詳細內容</button></a></td>
                    <td>
                        <form action='activity_list.php' method='post' onsubmit='submitForm(event)'>
                            <input type='hidden' name='activity_id' value='{$activity_id}'> <!-- 正確將 activity_id 傳遞到表單中 -->
                            ";

                    if ($row['is_hidden'] == 1) {
                        // 如果活动已隐藏，显示显示按钮
                        echo "<button type='submit' name='show_button' class='okbtn'>顯示 <i class='fa-solid fa-eye'></i></button>";
                    } else {
                        // 否则显示隐藏按钮
                        echo "<button type='submit' name='hide_button' class='okbtn'>隱藏 <i class='fa-solid fa-eye-slash'></i></button>";
                    }

                    echo "
                        </form>
                    </td>
                </tr>";
                    $cnt++;
                }
                ?>

            </tbody>
        </table>
    </div>
</body>

</html>