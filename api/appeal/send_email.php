<?php
require_once '../../db_config.php';
require_once '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 获取待审核ID和审核决定
$id = $_POST['id'];
$decision = $_POST['decision'];
$content = $_POST['content'];

// 查询待审核信息
$query = "SELECT * FROM qzy_appeal_pending WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // 更新数据库中的审核状态和不通过原因（如果有）
    if ($decision === 'approve') {
        $query = "UPDATE qzy_appeal_pending SET approved = 1 WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($decision === 'reject') {
        $query = "DELETE FROM qzy_appeal_pending WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // 发送邮件
    $to = $row['contact_email'];

    // 从qzy_config表中读取发送邮箱地址和授权密码
    $query = "SELECT * FROM qzy_config";
    $result = $db->query($query);
    $config = $result->fetch_assoc();

    $from = $config['email'];
    $password = $config['email_password'];
    $subject = "云黑审核结果通知";
    $message = '';

    if ($decision === 'approve') {
        $message = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    .container {
                        padding: 20px;
                        background-color: #FEE6F0;
                        font-family: Arial, sans-serif;
                    }
                    .content {
                        background-color: #FFFFFF;
                        padding: 20px;
                        border-radius: 5px;
                    }
                    .header {
                        background-color: #FBB2C8;
                        color: #FFFFFF;
                        padding: 10px;
                        border-radius: 5px 5px 0 0;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 24px;
                        font-weight: bold;
                    }
                    .message {
                        margin-top: 20px;
                        font-size: 16px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='content'>
                        <div class='header'>
                            <h1>云黑审核结果通知</h1>
                        </div>
                        <div class='message'>
                            <p>亲爱的用户，您的云黑申诉已通过审核。</p>
                            <p>您的黑名单信息已经删除，请注意保持诚信交易。</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";
    } elseif ($decision === 'reject') {
        $message = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    .container {
                        padding: 20px;
                        background-color: #FEE6F0;
                        font-family: Arial, sans-serif;
                    }
                    .content {
                        background-color: #FFFFFF;
                        padding: 20px;
                        border-radius: 5px;
                    }
                    .header {
                        background-color: #FBB2C8;
                        color: #FFFFFF;
                        padding: 10px;
                        border-radius: 5px 5px 0 0;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 24px;
                        font-weight: bold;
                    }
                    .message {
                        margin-top: 20px;
                        font-size: 16px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='content'>
                        <div class='header'>
                            <h1>云黑审核结果通知</h1>
                        </div>
                        <div class='message'>
                            <p>亲爱的用户，很遗憾！您的云黑申诉未通过审核。</p>
                            <p>不通过原因：<br>" . $content . "</p>
                            <p>如果存在疑问，可以点击以下链接加入频道进行询问</p>
                            <p><a>点击链接加入QQ频道【轻之忆网络工作室】：https://pd.qq.com/s/b4xfa060a</a></p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";
    }

    $mail = new PHPMailer(true);

    try {
        // 配置 SMTP 服务器
        $mail->isSMTP();
        $mail->Host = "smtp.163.com"; // 替换为您的 SMTP 服务器地址
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        // 配置发件人和收件人
        $mail->setFrom($from);
        $mail->addAddress($to);

        // 设置邮件内容
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8"; // 设置邮件字符编码为 UTF-8
        $mail->Subject = $subject;
        $mail->Body = $message;

        // 发送邮件
        $mail->send();

        // 返回成功响应
        echo json_encode(['status' => 'success', 'message' => '审核结果已发送邮件']);
    } catch (Exception $e) {
        // 返回错误响应
        echo json_encode(['status' => 'error', 'message' => '发送邮件失败']);
    }
} else {
    // 返回错误响应
    echo json_encode(['status' => 'error', 'message' => '找不到待审核信息']);
}
?>
