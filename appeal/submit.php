<?php
// 引入数据库配置文件
require_once '../db_config.php';

// 处理图片上传
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cloudBlackInfo = sanitizeInput($_POST['cloudBlackInfo']);
    $cloudBlackReason = sanitizeInput($_POST['cloudBlackReason']);
    $appealReason = sanitizeInput($_POST['appealReason']);
    $contactEmail = sanitizeInput($_POST['contactEmail']);

    // 验证上传的文件是否为图片
    $imagePaths = [];
    if (!empty($_FILES['cloudBlackEvidence'])) {
        $imageFiles = $_FILES['cloudBlackEvidence'];
        $numFiles = count($imageFiles['name']);
        for ($i = 0; $i < $numFiles; $i++) {
            $imageName = $imageFiles['name'][$i];
            $imageTmpName = $imageFiles['tmp_name'][$i];
            $imageType = $imageFiles['type'][$i];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // 验证文件类型
            $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                $response = [
                    'success' => false,
                    'message' => '上传的文件类型不支持，只支持上传图片文件。',
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }

            // 生成唯一的文件名
            $uniqueFilename = uniqid() . '_' . $imageName;

            // 上传图片并获取图片路径
            $uploadPath = '../upload/' . $uniqueFilename;
            if (move_uploaded_file($imageTmpName, $uploadPath)) {
                $imagePaths[] = $uploadPath;
            }
        }
    }

    // 将图片路径存储到数据库
    $imagePathsStr = implode('#', $imagePaths);

    // 防止 SQL 注入
    $cloudBlackInfo = $db->real_escape_string($cloudBlackInfo);
    $cloudBlackReason = $db->real_escape_string($cloudBlackReason);
    $appealReason = $db->real_escape_string($appealReason);
    $contactEmail = $db->real_escape_string($contactEmail);

    // 插入数据到数据库
    $sql = "INSERT INTO qzy_appeal_pending (black_info, black_reason, appeal_reason, appeal_evidence, contact_email)
          VALUES ('$cloudBlackInfo', '$cloudBlackReason', '$appealReason', '$imagePathsStr', '$contactEmail')";

    if ($db->query($sql) === TRUE) {
        $response = [
            'success' => true,
            'message' => '申请成功',
        ];
    } else {
        $response = [
            'success' => false,
            'message' => '申请失败',
        ];
    }

    // 返回 JSON 格式的响应
    header('Content-Type: application/json');
    echo json_encode($response);
}

// 关闭数据库连接
$db->close();

// 函数用于对输入数据进行安全处理
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
?>
