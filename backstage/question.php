<?php
require_once('conn.php');

// 查询数据库并获取最大的 question_id
$sql = "SELECT MAX(question_id) as max_id FROM `question`";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$maxQuestionId = (int)$row['max_id'];


// 获取数据库中的行数
$sql = "SELECT COUNT(*) as total FROM `question`";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$totalRows = (int)$row['total'];
if (!empty($_GET['question']) && !empty($_GET['answer']) && $totalRows < 3) {
    // 检查是否是 POST 请求
    $question = $_GET['question'];
    $answer = $_GET['answer'];

    // 验证和清理输入数据
    $question = mysqli_real_escape_string($conn, $question);
    $answer = mysqli_real_escape_string($conn, $answer);

    // 准备 INSERT 语句
    $sql = "INSERT INTO `question` (question, answer) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // 将值绑定到准备好的语句
    mysqli_stmt_bind_param($stmt, "ss", $question, $answer);

    if (mysqli_stmt_execute($stmt)) {
        // 插入成功
    } else {
        // 插入期间发生错误
        echo "添加問題时出錯：" . mysqli_stmt_error($stmt);
        var_dump($question, $answer); // 输出变量的值以进行调试
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<style>
    body {
        font-family: sans-serif;
        font-size: 18px;
    }

    h1 {
        font-size: 24px;
        font-weight: bold;
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

    .text {
        text-align: start;
    }

    .btn {
        background-color: #ff0000;
        color: white;
        padding: 10px;
        border-radius: 5px;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>常見問題管理</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <h1>常見問題管理</h1>
    <hr>
    <div class="add-question">
        <h3>新增問題</h3>

        <form action="question.php" method="$_GET" onsubmit="searchVolunteers(event)">
            <label for="question">問題：</label>
            <input type="text" name="question" id="question" required>
            <label for="answer">答案：</label>
            <input type="text" name="answer" id="answer" required>
            <?php
            if ($totalRows < 3) {
                // 只有当数据库中的数据行数小于3时才显示提交按钮
                echo '<button type="submit">新增</button>';
            } else {
                echo '<button type="submit" disabled>新增</button>';
                echo     '<p>最多同時存在3筆資料<p>';
            }
            ?>
        </form>
        <br>
    </div>

    <div class="showdata">
        <table border="1" cellpadding="10" style="text-align: center;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>問題</th>
                    <th>答案</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 查询数据库并显示数据
                $sql = "SELECT * FROM `question`";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res); // 获取总行数


                while ($row = mysqli_fetch_assoc($res)) {
                    // 确保 $row 数组中存在 'question_id' 键。
                    if (isset($row['question_id'])) {
                        $question_id = $row['question_id'];
                        echo "<tr>
                            <td>{$row['question_id']}</td>
                            <td class='text'>{$row['question']}</td>
                            <td class='text'>{$row['answer']}</td>
                            <td>
                                <button class='btn'>更改</button>
                            </td>
                            <td>
                            <button class='btn delete-btn' data-question-id='<?php echo $question_id; ?>'>删除</button>
                            </td>
                        </tr>";
                    }
                }

                ?>
            </tbody>
            <script>
                const deleteButtons = document.querySelectorAll('.delete-btn');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const questionId = this.getAttribute('data-question-id');
                        if (confirm('確定要刪除這個問題嗎？')) {
                            // 发送 AJAX 请求来删除问题
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', 'delete_question.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4) {
                                    if (xhr.status === 200) {
                                        // 删除成功后，刷新页面以更新表格
                                        location.reload();
                                    } else {
                                        alert('刪除問題時出現錯誤');
                                    }
                                }
                            };

                            xhr.send('question_id=' + questionId);
                        }
                    });
                });
            </script>


        </table>
    </div>
</body>

</html>