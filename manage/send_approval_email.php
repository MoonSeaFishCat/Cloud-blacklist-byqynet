<?php
// 导入 PHPMailer 类和异常类
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 导入数据库配置
require_once('../db_config.php');

// 导入 PHPMailer 的自动加载器
require '../vendor/autoload.php';

// 获取待审核信息的联系邮箱和审核不通过原因
$id = $_POST['id'];
$reason = $_POST['reason'];

// 获取登录用户名
session_start();

// 获取登录的用户名
$loggedInUsername = $_SESSION['username'];

// 查询数据库获取绑定的 QQ
$query = "SELECT bind_qq FROM qzy_user WHERE Username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();

// 获取查询结果
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $bindQQ = $row['bind_qq'];

  // 构建响应数据
  $response = [
    'success' => true,
     "username"=> $loggedInUsername,
     'bind_qq' => $bindQQ
  ];
} else {
  $response = [
    'success' => false,
    'message' => '未找到绑定的 QQ'
  ];
}

// 获取请求参数
$id = $_POST['id'];

// 查询待审核信息的详细数据
$query = "SELECT * FROM qzy_blacklist_pending WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();

  // 获取发件人邮箱和密码
  $emailQuery = "SELECT email, email_password FROM qzy_config LIMIT 1";
  $emailResult = $db->query($emailQuery);
  if ($emailResult->num_rows > 0) {
    $emailRow = $emailResult->fetch_assoc();
    $senderEmail = $emailRow['email'];
    $senderPassword = $emailRow['email_password'];

    // 将待审核信息写入云黑信息表
    $cloudBlackInfo = $row['cloud_black_info'];
    $cloudBlackReason = $row['cloud_black_reason'];
    $scammedAmount = $row['scammed_amount'];
    $contactEmail = $row['contact_email'];
    $imagePaths = $row['image_paths'];
    $cloudBlackLevel = isset($_POST['cloud_black_level']) ? $_POST['cloud_black_level'] : 0; // 设置默认值为0

    // 执行插入操作，并使用预处理语句进行参数绑定
    $insertQuery = "INSERT INTO qzy_blacklist (cloud_black_info, cloud_black_reason, scammed_amount, contact_email, image_paths, cloud_black_level) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($insertQuery);
    $stmt->bind_param("sssssi", $cloudBlackInfo, $cloudBlackReason, $scammedAmount, $contactEmail, $imagePaths, $cloudBlackLevel);
    $insertResult = $stmt->execute();

    if ($insertResult) {
      // 发送审核通过的邮件通知
      $mail = new PHPMailer(true);
      try {
        // 配置 SMTP 设置
        $mail->isSMTP();
        $mail->Host = 'smtp.163.com'; // 你的 SMTP 服务器地址
        $mail->SMTPAuth = true;
        $mail->Username = $senderEmail; // 发件人邮箱账号
        $mail->Password = $senderPassword; // 发件人邮箱密码
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // 设置发件人和收件人
        $mail->setFrom($senderEmail, '轻之忆'); // 发件人名称
        $mail->addAddress($contactEmail); // 收件人邮箱

        // 设置邮件内容
        $mail->CharSet = 'UTF-8'; // 设置字符集为 UTF-8
        $mail->isHTML(true);
        $mail->Subject = '轻之忆云端黑名单通知 - 审核通过';
        $mail->Body = '
          <div style="background-color: #FEE6F0; padding: 20px;">
            <h1 style="color: #FBB2C8; text-align: center;">轻之忆云端黑名单通知</h1>
            <div style="background-color: #FFF2F7; padding: 20px; border-radius: 10px;">
              <h2 style="color: #FBB2C8;">审核通过</h2>
              <p>尊敬的用户，您的云黑信息已审核通过。</p>
              <p>感谢您的理解和支持。</p>
              <li>审核负责人: ' . $loggedInUsername . '</li>
            </div>
          </div>
        ';

        // 发送邮件
        $mail->send();

        // 删除待审核信息
        $deleteQuery = "DELETE FROM qzy_blacklist_pending WHERE id = ?";
        $stmtDelete = $db->prepare($deleteQuery);
        $stmtDelete->bind_param("s", $id);
        $deleteResult = $stmtDelete->execute();

        if ($deleteResult) {
          // 执行备份表的插入操作
          $insertBackupQuery = "INSERT INTO `qzy_Cloud black information backup` (id, cloud_black_info, cloud_black_reason, scammed_amount, contact_email, image_paths) VALUES (?, ?, ?, ?, ?, ?)";
          $stmtBackup = $db->prepare($insertBackupQuery);
          $stmtBackup->bind_param("isssss", $id, $cloudBlackInfo, $cloudBlackReason, $scammedAmount, $contactEmail, $imagePaths);
          $insertBackupResult = $stmtBackup->execute();

          if ($insertBackupResult) {
            echo json_encode(array(
              "success" => true,
              "message" => "审核通过，邮件发送成功"
            ));
          } else {
            echo json_encode(array(
              "success" => false,
              "message" => "审核通过，邮件发送成功"
            ));
          }
        } else {
          echo json_encode(array(
            "success" => false,
            "message" => "审核通过，邮件发送成功，但删除待审核信息失败"
          ));
        }
      } catch (Exception $e) {
        echo json_encode(array(
          "success" => false,
          "message" => "审核通过，邮件发送失败：" . $mail->ErrorInfo
        ));
      }
    } else {
      echo json_encode(array(
        "success" => false,
        "message" => "审核通过，数据写入失败"
      ));
    }
  } else {
    echo json_encode(array(
      "success" => false,
      "message" => "未找到发件人邮箱和密码"
    ));
  }
} else {
  echo json_encode(array(
    "success" => false,
    "message" => "未找到对应的待审核信息"
  ));
}

$stmt->close();
$db->close();
?>
