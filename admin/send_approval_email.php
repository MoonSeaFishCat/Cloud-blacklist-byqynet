<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../db_config.php');
require '../vendor/autoload.php';

$response = [
    "success" => false,
    "message" => ""
];

function fetchPendingEntry($db, $id) {
    $query = "SELECT * FROM qzy_blacklist_pending WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
}

function fetchEmailConfig($db) {
    $emailQuery = "SELECT email, email_password FROM qzy_config LIMIT 1";
    return $db->query($emailQuery)->fetch_assoc() ?: null;
}

function insertBlacklist($db, $entry, $cloudBlackLevel) {
    $insertQuery = "INSERT INTO qzy_blacklist (cloud_black_info, cloud_black_reason, scammed_amount, contact_email, image_paths, cloud_black_level) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($insertQuery);
    $stmt->bind_param("sssssi", $entry['cloud_black_info'], $entry['cloud_black_reason'], $entry['scammed_amount'], $entry['contact_email'], $entry['image_paths'], $cloudBlackLevel);
    return $stmt->execute();
}

function deletePendingEntry($db, $id) {
    $deleteQuery = "DELETE FROM qzy_blacklist_pending WHERE id = ?";
    $stmt = $db->prepare($deleteQuery);
    $stmt->bind_param("s", $id);
    return $stmt->execute();
}

function sendNotificationEmail($emailConfig, $recipient) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig['email'];
        $mail->Password = $emailConfig['email_password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom($emailConfig['email'], '轻之忆');
        $mail->addAddress($recipient);

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = '轻之忆云端黑名单通知 - 审核通过';
        $mail->Body = 
        '<div style="background-color: #E6E6FA; padding: 20px; position: relative;">
        <div style="background-color: #D8BFD8; padding: 20px; border-radius: 10px;">
        <h1 style="color: #8A2BE2; text-align: center;">轻之忆云端黑名单通知</h1>
        <div style="background-color: #F8F8FF; padding: 20px; border-radius: 10px;">
            <h2 style="color: #8A2BE2;">审核通过</h2>
            <p>尊敬的用户，您的云黑信息审核已经通过，信息已经录入！</p>
            <p>感谢您的理解和支持。</p>
        </div>
    </div>
</div>
';

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

$pendingEntry = fetchPendingEntry($db, $_POST['id']);
if (!$pendingEntry) {
    $response['message'] = "未找到对应的云黑待审核信息";
    exit(json_encode($response));
}

$emailConfig = fetchEmailConfig($db);
if (!$emailConfig) {
    $response['message'] = "未找到发件人邮箱和密码";
    exit(json_encode($response));
}

$cloudBlackLevel = $_POST['cloud_black_level'] ?? 0;

if (insertBlacklist($db, $pendingEntry, $cloudBlackLevel)) {
    if (deletePendingEntry($db, $_POST['id'])) {
        if (sendNotificationEmail($emailConfig, $pendingEntry['contact_email'])) {
            $response['success'] = true;
            $response['message'] = "审核通过，邮件发送成功";
        } else {
            $response['message'] = "审核通过，但邮件发送失败";
        }
    } else {
        $response['message'] = "审核通过，但删除待审核信息失败";
    }
} else {
    $response['message'] = "审核通过，但数据写入失败";
}

echo json_encode($response);
$db->close();
