<?php
// 导入 PHPMailer 类和异常类
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../db_config.php');
require '../vendor/autoload.php';

function fetchPendingEmail($db, $id) {
    $query = "SELECT contact_email FROM qzy_blacklist_pending WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc()['contact_email'] : null;
}

function fetchSenderConfig($db) {
    $emailQuery = "SELECT email, email_password FROM qzy_config LIMIT 1";
    return $db->query($emailQuery)->fetch_assoc() ?: null;
}

function deletePendingEntry($db, $id) {
    $deleteQuery = "DELETE FROM qzy_blacklist_pending WHERE id = ?";
    $stmt = $db->prepare($deleteQuery);
    $stmt->bind_param("s", $id);
    return $stmt->execute();
}

function sendEmail($senderConfig, $recipient, $reason) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;
        $mail->Username = $senderConfig['email'];
        $mail->Password = $senderConfig['email_password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom($senderConfig['email'], '轻之忆');
        $mail->addAddress($recipient);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = '轻之忆云端黑名单通知 - 审核不通过';
        $mail->Body = '


 ';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$contactEmail = fetchPendingEmail($db, $_POST['id']);
$senderConfig = fetchSenderConfig($db);
$reason = $_POST['reason'];

if (!$contactEmail) {
    exit(json_encode(["success" => false, "message" => "未找到待审核信息"]));
}

if (!$senderConfig) {
    exit(json_encode(["success" => false, "message" => "未找到发件人邮箱和密码"]));
}

$emailSent = sendEmail($senderConfig, $contactEmail, $reason);

if (deletePendingEntry($db, $_POST['id'])) {
    if ($emailSent) {
        echo json_encode(["success" => true, "message" => "邮件发送成功，待审核信息已删除"]);
    } else {
        echo json_encode(["success" => false, "message" => "邮件发送失败，但待审核信息已删除"]);
    }
} else {
    if ($emailSent) {
        echo json_encode(["success" => false, "message" => "邮件发送成功，但删除待审核信息失败"]);
    } else {
        echo json_encode(["success" => false, "message" => "邮件发送失败且删除待审核信息失败"]);
    }
}

$db->close();
?>
