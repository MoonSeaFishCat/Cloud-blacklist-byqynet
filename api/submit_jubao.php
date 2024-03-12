<?php
// 引入数据库配置文件
require_once '../db_config.php';

// 处理图片上传
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cloudBlackInfo = sanitizeInput($_POST['cloudBlackInfo']);
    $cloudBlackReason = sanitizeInput($_POST['cloudBlackReason']);
    $scammedAmount = floatval($_POST['scammedAmount']);
    $contactEmail = sanitizeInput($_POST['contactEmail']);

    // Create a directory to store uploaded images (root-level "upload" folder)
    $uploadDirectory = __DIR__ . '/../upload/';
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

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

            // Generate an MD5 hash as the new name for the image
            $md5Name = md5_file($imageTmpName) . '.' . $fileExtension;
            $uploadPath = $uploadDirectory . $md5Name;

            // Move the uploaded image to the new MD5 name
            if (move_uploaded_file($imageTmpName, $uploadPath)) {
                $imagePaths[] = '../upload/' . $md5Name;
            }
        }
    }

    // 将图片路径存储到数据库
    $imagePathsStr = implode('#', $imagePaths);

    // 防止SQL注入
    $cloudBlackInfo = $db->real_escape_string($cloudBlackInfo);
    $cloudBlackReason = $db->real_escape_string($cloudBlackReason);
    $contactEmail = $db->real_escape_string($contactEmail);

    // 插入数据到数据库
    $sql = "INSERT INTO qzy_blacklist_pending (cloud_black_info, cloud_black_reason, scammed_amount, contact_email, image_paths)
          VALUES ('$cloudBlackInfo', '$cloudBlackReason', $scammedAmount, '$contactEmail', '$imagePathsStr')";

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
