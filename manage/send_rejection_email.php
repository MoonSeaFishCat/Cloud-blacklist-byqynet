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

$query = "SELECT * FROM qzy_blacklist_pending WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $contactEmail = $row['contact_email'];

  // 获取发件人邮箱和密码
  $emailQuery = "SELECT email, email_password FROM qzy_config LIMIT 1";
  $emailResult = $db->query($emailQuery);
  if ($emailResult->num_rows > 0) {
    $emailRow = $emailResult->fetch_assoc();
    $senderEmail = $emailRow['email'];
    $senderPassword = $emailRow['email_password'];

    // 邮件配置
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
      $mail->Subject = '轻之忆云端黑名单通知 - 审核不通过';
      $mail->Body = '
        <div style="background-color: #FEE6F0; padding: 20px;">
          <h1 style="color: #FBB2C8; text-align: center;">轻之忆云端黑名单通知</h1>
          <div style="background-color: #FFF2F7; padding: 20px; border-radius: 10px;">
            <h2 style="color: #FBB2C8;">审核不通过</h2>
            <p>尊敬的用户，您的云黑信息审核不通过。请查看以下详情：</p>
            <ul>
              <li>审核人: ' . $$loggedInUsername  . '</li>
              <li>审核不通过的原因: ' . $reason . '</li>
            </ul>
            <p>感谢您的理解和支持。</p>
          </div>
        </div>
      ';

      // 发送邮件
      $mail->send();

      // 删除待审核信息
      $deleteQuery = "DELETE FROM qzy_blacklist_pending WHERE id = ?";
      $stmt = $db->prepare($deleteQuery);
      $stmt->bind_param("s", $id);
      $deleteResult = $stmt->execute();

      if ($deleteResult) {
        // 执行备份表的插入操作
        $insertBackupQuery = "INSERT INTO qzy_cloud_black_information_backup (id, cloud_black_info, cloud_black_reason, scammed_amount, contact_email, image_paths, reason, auditor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtBackup = $db->prepare($insertBackupQuery);
        $stmtBackup->bind_param("isssssss", $id, $row['cloud_black_info'], $row['cloud_black_reason'], $row['scammed_amount'], $row['contact_email'], $row['image_paths'], $reason, $auditor);
        $insertBackupResult = $stmtBackup->execute();

        if ($insertBackupResult) {
          echo json_encode(array(
            "success" => true,
            "message" => "邮件发送成功，待审核信息已删除，数据备份成功"
          ));
        } else {
          echo json_encode(array(
            "success" => false,
            "message" => "邮件发送成功，待审核信息已删除，数据备份失败"
          ));
        }
      } else {
        echo json_encode(array(
          "success" => false,
          "message" => "邮件发送成功，但删除待审核信息失败"
        ));
      }
    } catch (Exception $e) {
      echo json_encode(array(
        "success" => false,
        "message" => "邮件发送失败：" . $mail->ErrorInfo
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
    "message" => "未找到待审核信息"
  ));
}

$stmt->close();
$db->close();
?>