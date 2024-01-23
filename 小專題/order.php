<?php
    require_once('conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>訂單</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="form">
            <form action="order.php" method="get">
                訂單日期：<input class="date" type="date" name="date" >
                
                訂單編號：<input class="search" type="text" name="ono" size="8" placeholder="請輸入編號">
                
                客戶編號：<input class="search" type="text" name="cid" size="8" placeholder="請輸入編號">
                
                商品編號：<input class="search" type="text" name="pno" size="8" placeholder="請輸入編號">
                <button type="submit" class="btn">搜尋 <i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <hr>
        <div class="right">
            <button onclick="location.href='orderadd.php'" class="btn">新增 <i class="fa-regular fa-square-plus"></i></i></button>
        </div>
        <table border="1" cellpadding="20">
            <tr>
                <td>訂單編號</td>
                <td>訂購日期</td>
                <td>客戶編號</td>
                <td>訂購數量</td>
                <td>商品編號</td>
                <td colspan="2">狀態</td>
            </tr>
            <tr>
                <?php
                if(!empty($_GET['date'])){
                    $select=$_GET['date'];
                    $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `oDate` like '%$select%' AND `deducted` = 0 ;";//找還沒出貨的訂單
                    $res = mysqli_query($conn, $sql);
            
                    while ($row = mysqli_fetch_assoc($res)) {
                        $ono = $row['oNo'];
                        $pid = $row['pId'];
            
                        // 检查是否为组合商品
                        $sql1 = "SELECT * FROM `product` WHERE `bId` = '$pid';";
                        $res1 = mysqli_query($conn, $sql1);
                        $isBundle = mysqli_num_rows($res1) > 0;
            
                        $allowship = true;
            
                        if ($isBundle) {
                            // 如果是组合商品，检查每个组成零件的库存和预扣
                            while ($row1 = mysqli_fetch_assoc($res1)) {
                                $partPid = $row1['pId'];
                                $sqlStock = "SELECT * FROM stock WHERE pId = '$partPid'";
                                $resStock = mysqli_query($conn, $sqlStock);
                                $stock = mysqli_fetch_assoc($resStock);
            
                                $sq = $stock['sQuantity']; // 真实库存量
                                $aq = $stock['sAdvance']; // 预扣
            
                                if ($row['oQuantity'] > ($sq)) {
                                    $allowship = false;
                                    break;
                                }
                            }
                        } else {
                            // 如果不是组合商品，只需检查单个商品的库存和预扣
                            $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                            $resStock = mysqli_query($conn, $sqlStock);
                            $stock = mysqli_fetch_assoc($resStock);
            
                            $sq = $stock['sQuantity']; // 真实库存量
                            $aq = $stock['sAdvance']; // 预扣
            
                            if ($row['oQuantity'] > ($sq)) {
                                $allowship = false;
                            }
                        }
            
                        echo "<tr>
                                <td>{$row['oNo']}</td>
                                <td>{$row['oDate']}</td>
                                <td>{$row['cId']}</td>
                                <td>{$row['oQuantity']}</td>
                                <td>{$row['pId']}</td>
                                <td>
                                    <form method='POST' action='order.php'>
                                        <input type='hidden' name='deducted[]' value='{$row['oNo']}'>"; // 注意这里的name属性是deducted[]，表示将多个订单号作为数组传递
            
                        if ($allowship) {
                            echo "<button type='submit' class='btn' name='submit'>出貨</button>";
                        }
            
                        echo "<input type='hidden' name='delete' value='{$row['oNo']}'>
                            <button type='submit' class='btn' name='delsubmit'>取消訂單</button>
                            </form>
                            </td>";
            
                        // 显示状态
                        if ($isBundle) {
                            if ($allowship) {
                                echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='' data-content='可出貨'>查看庫存</a></td>";
                            } else {
                                echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='無法出貨' data-content='庫存不足'>查看庫存</a></td>";
                            }
                        } else {
                            if ($stock['sSafety'] < $stock['sAdvance']) {
                                echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='預扣庫存' data-content='庫存剩餘：{$stock['sAdvance']}'>查看庫存</a></td>";
                            } else {
                                echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='請盡快補貨' data-content='庫存剩餘：{$stock['sAdvance']}'>低於安全庫存</a></td>";
                            }
                        }
            
                        echo "</tr>";

                }
            }
            elseif(!empty($_GET['ono'])){
                $select=$_GET['ono'];
                $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `oNo` like '%$select%' AND `deducted` = 0 ;";//找還沒出貨的訂單
                $res = mysqli_query($conn, $sql);
        
                while ($row = mysqli_fetch_assoc($res)) {
                    $ono = $row['oNo'];
                    $pid = $row['pId'];
        
                    // 检查是否为组合商品
                    $sql1 = "SELECT * FROM `product` WHERE `bId` = '$pid';";
                    $res1 = mysqli_query($conn, $sql1);
                    $isBundle = mysqli_num_rows($res1) > 0;
        
                    $allowship = true;
        
                    if ($isBundle) {
                        // 如果是组合商品，检查每个组成零件的库存和预扣
                        while ($row1 = mysqli_fetch_assoc($res1)) {
                            $partPid = $row1['pId'];
                            $sqlStock = "SELECT * FROM stock WHERE pId = '$partPid'";
                            $resStock = mysqli_query($conn, $sqlStock);
                            $stock = mysqli_fetch_assoc($resStock);
        
                            $sq = $stock['sQuantity']; // 真实库存量
                            $aq = $stock['sAdvance']; // 预扣
        
                            if ($row['oQuantity'] > ($sq)) {
                                $allowship = false;
                                break;
                            }
                        }
                    } else {
                        // 如果不是组合商品，只需检查单个商品的库存和预扣
                        $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                        $resStock = mysqli_query($conn, $sqlStock);
                        $stock = mysqli_fetch_assoc($resStock);
        
                        $sq = $stock['sQuantity']; // 真实库存量
                        $aq = $stock['sAdvance']; // 预扣
        
                        if ($row['oQuantity'] > ($sq )) {
                            $allowship = false;
                        }
                    }
        
                    echo "<tr>
                            <td>{$row['oNo']}</td>
                            <td>{$row['oDate']}</td>
                            <td>{$row['cId']}</td>
                            <td>{$row['oQuantity']}</td>
                            <td>{$row['pId']}</td>
                            <td>
                                <form method='POST' action='order.php'>
                                    <input type='hidden' name='deducted[]' value='{$row['oNo']}'>"; // 注意这里的name属性是deducted[]，表示将多个订单号作为数组传递
        
                    if ($allowship) {
                        echo "<button type='submit' class='btn' name='submit'>出貨</button>";
                    }
        
                    echo "<input type='hidden' name='delete' value='{$row['oNo']}'>
                        <button type='submit' class='btn' name='delsubmit'>取消訂單</button>
                        </form>
                        </td>";
        
                    // 显示状态
                    if ($isBundle) {
                        if ($allowship) {
                            echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='' data-content='可出貨'>查看庫存</a></td>";
                        } else {
                            echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='無法出貨' data-content='庫存不足'>查看庫存</a></td>";
                        }
                    } else {
                        if ($stock['sSafety'] < $stock['sAdvance']) {
                            echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='預扣庫存' data-content='庫存剩餘：{$stock['sAdvance']}'>查看庫存</a></td>";
                        } else {
                            echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='請盡快補貨' data-content='庫存剩餘：{$stock['sAdvance']}'>低於安全庫存</a></td>";
                        }
                    }
        
                    echo "</tr>";

            }
        }
        elseif(!empty($_GET['cid'])){
            $select=$_GET['cid'];
            $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `cId` like '%$select%' AND `deducted` = 0 ;";//找還沒出貨的訂單
            $res = mysqli_query($conn, $sql);
    
            while ($row = mysqli_fetch_assoc($res)) {
                $ono = $row['oNo'];
                $pid = $row['pId'];
    
                // 检查是否为组合商品
                $sql1 = "SELECT * FROM `product` WHERE `bId` = '$pid';";
                $res1 = mysqli_query($conn, $sql1);
                $isBundle = mysqli_num_rows($res1) > 0;
    
                $allowship = true;
    
                if ($isBundle) {
                    // 如果是组合商品，检查每个组成零件的库存和预扣
                    while ($row1 = mysqli_fetch_assoc($res1)) {
                        $partPid = $row1['pId'];
                        $sqlStock = "SELECT * FROM stock WHERE pId = '$partPid'";
                        $resStock = mysqli_query($conn, $sqlStock);
                        $stock = mysqli_fetch_assoc($resStock);
    
                        $sq = $stock['sQuantity']; // 真实库存量
                        $aq = $stock['sAdvance']; // 预扣
    
                        if ($row['oQuantity'] > ($sq)) {
                            $allowship = false;
                            break;
                        }
                    }
                } else {
                    // 如果不是组合商品，只需检查单个商品的库存和预扣
                    $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                    $resStock = mysqli_query($conn, $sqlStock);
                    $stock = mysqli_fetch_assoc($resStock);
    
                    $sq = $stock['sQuantity']; // 真实库存量
                    $aq = $stock['sAdvance']; // 预扣
    
                    if ($row['oQuantity'] > ($sq)) {
                        $allowship = false;
                    }
                }
    
                echo "<tr>
                        <td>{$row['oNo']}</td>
                        <td>{$row['oDate']}</td>
                        <td>{$row['cId']}</td>
                        <td>{$row['oQuantity']}</td>
                        <td>{$row['pId']}</td>
                        <td>
                            <form method='POST' action='order.php'>
                                <input type='hidden' name='deducted[]' value='{$row['oNo']}'>"; // 注意这里的name属性是deducted[]，表示将多个订单号作为数组传递
    
                if ($allowship) {
                    echo "<button type='submit' class='btn' name='submit'>出貨</button>";
                }
    
                echo "<input type='hidden' name='delete' value='{$row['oNo']}'>
                    <button type='submit' class='btn' name='delsubmit'>取消訂單</button>
                    </form>
                    </td>";
    
                // 显示状态
                if ($isBundle) {
                    if ($allowship) {
                        echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='' data-content='可出貨'>查看庫存</a></td>";
                    } else {
                        echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='無法出貨' data-content='庫存不足'>查看庫存</a></td>";
                    }
                } else {
                    if ($stock['sSafety'] < $stock['sAdvance']) {
                        echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='預扣庫存' data-content='庫存剩餘：{$stock['sAdvance']}'>查看庫存</a></td>";
                    } else {
                        echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='請盡快補貨' data-content='庫存剩餘：{$stock['sAdvance']}'>低於安全庫存</a></td>";
                    }
                }
    
                echo "</tr>";

        }
    }
    elseif(!empty($_GET['pno'])){
        $select=$_GET['pno'];
        $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `pId` like '%$select%' AND `deducted` = 0 ;";//找還沒出貨的訂單
        $res = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($res)) {
            $ono = $row['oNo'];
            $pid = $row['pId'];

            // 检查是否为组合商品
            $sql1 = "SELECT * FROM `product` WHERE `bId` = '$pid';";
            $res1 = mysqli_query($conn, $sql1);
            $isBundle = mysqli_num_rows($res1) > 0;

            $allowship = true;

            if ($isBundle) {
                // 如果是组合商品，检查每个组成零件的库存和预扣
                while ($row1 = mysqli_fetch_assoc($res1)) {
                    $partPid = $row1['pId'];
                    $sqlStock = "SELECT * FROM stock WHERE pId = '$partPid'";
                    $resStock = mysqli_query($conn, $sqlStock);
                    $stock = mysqli_fetch_assoc($resStock);

                    $sq = $stock['sQuantity']; // 真实库存量
                    $aq = $stock['sAdvance']; // 预扣

                    if ($row['oQuantity'] > ($sq)) {
                        $allowship = false;
                        break;
                    }
                }
            } else {
                // 如果不是组合商品，只需检查单个商品的库存和预扣
                $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                $resStock = mysqli_query($conn, $sqlStock);
                $stock = mysqli_fetch_assoc($resStock);

                $sq = $stock['sQuantity']; // 真实库存量
                $aq = $stock['sAdvance']; // 预扣

                if ($row['oQuantity'] > ($sq)) {
                    $allowship = false;
                }
            }

            echo "<tr>
                    <td>{$row['oNo']}</td>
                    <td>{$row['oDate']}</td>
                    <td>{$row['cId']}</td>
                    <td>{$row['oQuantity']}</td>
                    <td>{$row['pId']}</td>
                    <td>
                        <form method='POST' action='order.php'>
                            <input type='hidden' name='deducted[]' value='{$row['oNo']}'>"; // 注意这里的name属性是deducted[]，表示将多个订单号作为数组传递

            if ($allowship) {
                echo "<button type='submit' class='btn' name='submit'>出貨</button>";
            }

            echo "<input type='hidden' name='delete' value='{$row['oNo']}'>
                <button type='submit' class='btn' name='delsubmit'>取消訂單</button>
                </form>
                </td>";

            // 显示状态
            if ($isBundle) {
                if ($allowship) {
                    echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='' data-content='可出貨'>查看庫存</a></td>";
                } else {
                    echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='無法出貨' data-content='庫存不足'>查看庫存</a></td>";
                }
            } else {
                if ($stock['sSafety'] < $stock['sAdvance']) {
                    echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='預扣庫存' data-content='庫存剩餘：{$stock['sAdvance']}'>查看庫存</a></td>";
                } else {
                    echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='請盡快補貨' data-content='庫存剩餘：{$stock['sAdvance']}'>低於安全庫存</a></td>";
                }
            }

            echo "</tr>";

    }
}
                else{
                  $sql = "SELECT * FROM `orders` NATURAL JOIN `details` WHERE `deducted` = 0 ;";//找還沒出貨的訂單
                  $res = mysqli_query($conn, $sql);
          
                  while ($row = mysqli_fetch_assoc($res)) {
                      $ono = $row['oNo'];
                      $pid = $row['pId'];
          
                      // 檢查是否為组合商品
                      $sql1 = "SELECT * FROM `product` WHERE `bId` = '$pid';";
                      $res1 = mysqli_query($conn, $sql1);
                      $isBundle = mysqli_num_rows($res1) > 0;
          
                      $allowship = true;
          
                      if ($isBundle) {
                          // 如果是组合商品，檢查每個组成零件的庫存
                          while ($row1 = mysqli_fetch_assoc($res1)) {
                              $partPid = $row1['pId'];
                              $sqlStock = "SELECT * FROM stock WHERE pId = '$partPid'";
                              $resStock = mysqli_query($conn, $sqlStock);
                              $stock = mysqli_fetch_assoc($resStock);
          
                              $sq = $stock['sQuantity']; // 真實庫存量
          
                              if ($row['oQuantity'] > ($sq)) {
                                  $allowship = false;
                                  break;
                              }
                          }
                      } else {
                          // 如果不是组合商品，只需檢查單個商品的庫存
                          $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                          $resStock = mysqli_query($conn, $sqlStock);
                          $stock = mysqli_fetch_assoc($resStock);
          
                          $sq = $stock['sQuantity']; // 真實庫存量
          
                          if ($row['oQuantity'] > ($sq)) {
                              $allowship = false;
                          }
                      }
          
                      echo "<tr>
                              <td>{$row['oNo']}</td>
                              <td>{$row['oDate']}</td>
                              <td>{$row['cId']}</td>
                              <td>{$row['oQuantity']}</td>
                              <td>{$row['pId']}</td>
                              <td>
                                  <form method='POST' action='order.php'>
                                      <input type='hidden' name='deducted[]' value='{$row['oNo']}'>"; 
          
                      if ($allowship) {
                          echo "<button type='submit' class='btn' name='submit'>出貨</button>";
                      }
          
                      echo "<input type='hidden' name='delete' value='{$row['oNo']}'>
                          <button type='submit' class='btn' name='delsubmit'>取消訂單</button>
                          </form>
                          </td>";
          
                      // 顯示狀態
                      if ($isBundle) {
                          if ($allowship) {
                              echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='' data-content='可出貨'>查看庫存</a></td>";
                          } else {
                              echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='無法出貨' data-content='庫存不足'>查看庫存</a></td>";
                          }
                      } else {
                          if ($stock['sSafety'] < $stock['sAdvance']) {
                              echo "<td><a tabindex='0' class='btn' role='button' data-toggle='popover' data-trigger='focus' title='預扣庫存' data-content='庫存剩餘：{$stock['sAdvance']}'>查看庫存</a></td>";
                          } else {
                              echo "<td><a tabindex='0' class='btn-danger' role='button' data-toggle='popover' data-trigger='focus' title='請盡快補貨' data-content='庫存剩餘：{$stock['sAdvance']}'>低於安全庫存</a></td>";
                          }
                      }
          
                      echo "</tr>";
                  }
          }
                  if (isset($_POST['submit'])) {
                    // 出貨
                    foreach ($_POST['deducted'] as $ono) {
                        $sql3 = "UPDATE `orders` SET `deducted` = 1 WHERE `oNo` = '$ono';";
                        $res3 = mysqli_query($conn, $sql3);
                
                        // 更新庫存和出貨時間
                        $sql4 = "SELECT * FROM `details` WHERE `oNo` = '$ono';";
                        $res4 = mysqli_query($conn, $sql4);
                
                        while ($row4 = mysqli_fetch_assoc($res4)) {
                            $pid = $row4['pId'];
                            $oq = $row4['oQuantity'];
                
                            // 檢查商品是否為組合
                            $sqlIsBundle = "SELECT * FROM `product` WHERE `bId` = '$pid'";
                            $resIsBundle = mysqli_query($conn, $sqlIsBundle);
                            $isBundle = mysqli_num_rows($resIsBundle) > 0;
                
                            if ($isBundle) {
                                // 扣除組合商品的庫存
                                $sqlBundle = "SELECT * FROM `product` WHERE `bId` = '$pid'";
                                $resBundle = mysqli_query($conn, $sqlBundle);
                
                                while ($bundleRow = mysqli_fetch_assoc($resBundle)) {
                                    $componentId = $bundleRow['pId'];
                
                                    $sqlComponentStock = "SELECT * FROM stock WHERE pId = '$componentId'";
                                    $resComponentStock = mysqli_query($conn, $sqlComponentStock);
                                    $componentStock = mysqli_fetch_assoc($resComponentStock);
                                    $componentSq = $componentStock['sQuantity']; // 真實庫存量
                                    $componentRq = $componentSq - $oq; // 出貨後的真實庫存
                
                                    $sqlComponentRq = "UPDATE `stock` SET `sQuantity`='$componentRq' WHERE `pId` = '$componentId'";
                                    $resComponentRq = mysqli_query($conn, $sqlComponentRq);
                                }
                            } else {
                                // 扣除單個商品的庫存
                                $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                                $resStock = mysqli_query($conn, $sqlStock);
                                $stock = mysqli_fetch_assoc($resStock);
                                $sq = $stock['sQuantity']; // 真實庫存量
                                $rq = $sq - $oq; // 出貨後的真實庫存
                
                                $sqlRq = "UPDATE `stock` SET `sQuantity`='$rq' WHERE `pId` = '$pid'";
                                $resRq = mysqli_query($conn, $sqlRq);
                            }
                
                            $sql6 = "UPDATE `orders` SET `sDate`= CURRENT_TIMESTAMP WHERE `oNo` = '$ono'";
                            $res6 = mysqli_query($conn, $sql6);
                        }
                    }
                }
                
                elseif (isset($_POST['delsubmit'])) {
                    // 處理取消訂單
                    $ono = $_POST['delete'];
                
                    // 將預扣返回
                    $sqlAdvance = "SELECT * FROM `details` NATURAL JOIN `product` WHERE `oNo` = '$ono';";
                    $resAdvance = mysqli_query($conn, $sqlAdvance);
                
                    while ($rowAdvance = mysqli_fetch_assoc($resAdvance)) {
                        $oq = $rowAdvance['oQuantity'];
                        $pid = $rowAdvance['pId'];
                
                        // 檢查商品是否為组合商品
                        $sqlisbundle = "SELECT * FROM product WHERE bId = '$pid'";
                        $resisbundle = mysqli_query($conn, $sqlisbundle);
                        $isbundle = mysqli_num_rows($resisbundle) > 0;
                
                        if ($isbundle) {
                            // 组合商品，返回零件庫存
                            $sqlBundle = "SELECT * FROM product WHERE bId = '$pid'";
                            $resBundle = mysqli_query($conn, $sqlBundle);
                
                            while ($bundleRow = mysqli_fetch_assoc($resBundle)) {
                                $bundlePid = $bundleRow['pId'];
                                $sqlStock = "SELECT * FROM stock WHERE pId = '$bundlePid'";
                                $resStock = mysqli_query($conn, $sqlStock);
                                $stock = mysqli_fetch_assoc($resStock);
                                $aq = $stock['sAdvance']; // 预扣
                                $newaq = $aq + $oq; // 取消後將預扣的數量加回去
                                $sqlUpdateStock = "UPDATE stock SET sAdvance = '$newaq' WHERE `pId` = '$bundlePid'";
                                mysqli_query($conn, $sqlUpdateStock);
                            }
                        } else {
                            // 非组合商品，直接返回庫存
                            $sqlStock = "SELECT * FROM stock WHERE pId = '$pid'";
                            $resStock = mysqli_query($conn, $sqlStock);
                            $stock = mysqli_fetch_assoc($resStock);
                            $aq = $stock['sAdvance']; // 预扣
                            $newaq = $aq + $oq; // 取消後將預扣的數量加回去
                            $sqlUpdateStock = "UPDATE stock SET sAdvance = '$newaq' WHERE `pId` = '$pid'";
                            mysqli_query($conn, $sqlUpdateStock);
                        }
                    }
                
                    // 删除訂單顯示
                    $sqlDetails = "DELETE FROM `details` WHERE `oNo` = '$ono';";
                    $resDetails = mysqli_query($conn, $sqlDetails);
                    $sql = "DELETE FROM `orders` WHERE `oNo` = '$ono';";
                    $res = mysqli_query($conn, $sql);
                }             
          
                  mysqli_close($conn);
                ?>
            </tr>
      </table>
</body>
</html>

<script>
  $(document).ready(function(){
    // 初始化弹出窗口
    $('[data-toggle="popover"]').popover({ 
      placement : 'left',
      trigger : 'focus',
      html : true,
      content: function() {
        return $('#popover-content').html();
      }
    });
  });
</script>