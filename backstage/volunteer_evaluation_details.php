<?php
require_once('conn.php');
$user_id = $_GET['user_id'];
$username = $_GET['username'];
$activity_id = $_GET['activity_id'];

$sql = "SELECT * FROM users_evaluate WHERE user_id = '{$user_id}' AND activity_id = '{$activity_id}'";
$res = mysqli_query($conn, $sql);

$rows = [];
while ($row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../assets/css/boxicon.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        td {
            min-width: 150px;
        }
    </style>
</head>

<body>
    <h3><a href="#" onclick="loadContent('volunteer_evaluation.php');" class="pgup"><i class="fa-solid fa-reply"></i>回志工評價</a></h3>
    <h1><?= $username ?> 志工評價</h1><br>
    <div class="showdetails-1">
        <table border="1" cellpadding="10">
            <tbody>
                <?php
                foreach ($rows as $row) {
                    echo "<tr>
                                <td>平台滿意度</td>
                                <td>";
                    $platform_satisfaction = $row['platform_satisfaction'];
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $platform_satisfaction) {
                            echo '<i class="bx bxs-star" style="color:gold;"></i>'; // 显示点亮的星星
                        } else {
                            echo '<i class="bx bxs-star" style="color: grey;"></i>'; // 显示未点亮的星星
                        }
                    }
                    echo "</td>
                            </tr>
                            <tr>
                                <td>流程滿意度</td>
                                <td>";
                    $process_satisfaction = $row['process_satisfaction'];
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $process_satisfaction) {
                            echo '<i class="bx bxs-star" style="color:gold;"></i>'; // 显示点亮的星星
                        } else {
                            echo '<i class="bx bxs-star" style="color: grey;"></i>'; // 显示未点亮的星星
                        }
                    }
                    echo "</td>
                            </tr>
                            <tr>
                                <td>活動滿意度</td>
                                <td>";
                    $activity_satisfaction = $row['activity_satisfaction'];
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $activity_satisfaction) {
                            echo '<i class="bx bxs-star" style="color:gold;"></i>'; // 显示点亮的星星
                        } else {
                            echo '<i class="bx bxs-star" style="color: grey;"></i>'; // 显示未点亮的星星
                        }
                    }
                    echo "</td>
                            </tr>
                            <tr>
                                <td>回饋內容</td>
                                <td>{$row['content']}</td>
                            </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


</body>

</html>