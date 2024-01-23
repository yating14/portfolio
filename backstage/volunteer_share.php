<?php
require_once('conn.php');
// 查询share所有資料
$sql = "SELECT * FROM `share` ORDER BY `share`.`exhibit` DESC";
$res = mysqli_query($conn, $sql);
$count = mysqli_num_rows($res); // 获取总行数

// 查询share所有展示的資料
$sql = "SELECT * FROM `share` WHERE exhibit = 1";
$result  = mysqli_query($conn, $sql);
$showcount = mysqli_num_rows($result); // 获取总行数

if (isset($_POST['del'])) {
    $share_id = $_POST['share_id'];
    $sql_del = "DELETE FROM share WHERE share_id = ?";
    $stmt = $conn->prepare($sql_del);
    $stmt->bind_param("i", $share_id);

    if ($stmt->execute()) {
        echo '<script>alert("刪除成功"); window.location.href = "index.html";</script>';
    } else {
        echo '<script>alert("刪除失敗");</script>';
    }
}

if (isset($_POST['hide_button'])) {
    $share_id = $_POST['share_id'];
    $update_sql = "UPDATE `share` SET `exhibit` = 0 WHERE `share_id` = '{$share_id}'";
    $result = mysqli_query($conn, $update_sql);
    if ($result) {
        echo '<script>alert("隱藏成功"); window.location.href = "index.html";</script>';
    } else {
        echo '<script>alert("隱藏失敗");</script>';
    }
}

if (isset($_POST['show_button'])) {
    $share_id = $_POST['share_id'];
    $update_sql = "UPDATE `share` SET `exhibit` = 1 WHERE `share_id` = '{$share_id}'";
    $result = mysqli_query($conn, $update_sql);
    if ($result) {
        echo '<script>alert("顯示成功"); window.location.href = "index.html";</script>';
    } else {
        echo '<script>alert("顯示失敗");</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>常見問題管理</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead {
            background-color: #ccc;
        }

        th,
        td {
            padding: 10px;
        }

        .commenttext {
            text-align: start;
            width: 70%;
        }

        .delbtn {
            background-color: #dc3545;
            border-color: transparent;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .delbtn:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <h1>志工分享管理</h1>
    <hr>
    <div class="showdata">
        <table border="1" cellpadding="10" style="text-align: center;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>志工名稱</th>
                    <th>分享內容</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo  "<h2>共 $count 筆</h2>";

                if ($showcount >= 3) {
                    echo '<h4>最多顯示3筆資料</h4>';
                }

                while ($row = mysqli_fetch_assoc($res)) {
                    // 确保 $row 数组中存在 'share_id' 键。
                    if (isset($row['share_id'])) {
                        $share_id = $row['share_id'];
                        echo "<tr>
                            <td>{$row['share_id']}</td>
                            <td>{$row['share_name']}</td>
                            <td class='commenttext'>{$row['comment']}</td>
                            <td>
                            <form action='volunteer_share.php' method='post' onsubmit='submitForm(event)'>
                                <input type='hidden' name='share_id' value='{$share_id}'>";
                        if ($row['exhibit'] == 0 && $showcount < 3) {
                            // 如果活动已隐藏，显示显示按钮
                            echo "<button name='show_button' class='okbtn'>顯示 <i class='fa-solid fa-eye'></i></button>";
                        } elseif ($row['exhibit'] == 0 && $showcount >= 3) {
                            echo "<button name='show_button' class='disabledbtn'>顯示 <i class='fa-solid fa-eye'></i></button>";
                        } elseif ($row['exhibit'] == 1) {
                            echo "<button type='submit' name='hide_button' class='okbtn'>隱藏 <i class='fa-solid fa-eye-slash'></i></button>";
                        }
                        echo "
                            </form>
                        </td>
                            <td>
                            <form action='volunteer_share.php' method='post' onsubmit='submitForm(event)'>
                            <input type='hidden' name='share_id' value='{$row['share_id']}'>
                            <button type='submit' name='del'  class='delbtn'>删除</button>
                            </form>
                            </td>
                        </tr>";
                    }
                }

                ?>
            </tbody>
        </table>
    </div>

    <script>
        function submitForm(event) {
            event.preventDefault(); // 阻止默认表单提交行为
            var form = event.target;
            var share_id = form.querySelector('input[name="share_id"]').value;
            var formData = new FormData(form);

            fetch('volunteer_share.php', { // 修改为正确的后端处理程序 URL
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
</body>

</html>