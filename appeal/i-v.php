<?php
// 包含数据库配置
require_once '../db_config.php';

// 函数用于检查输入是否符合条件
function checkCloudBlackInfo($cloud_black_level, $cloud_black_reason)
{
    // 检查云黑级别是否为4或者云黑原因是否包含 '#特殊云黑#' 字符
    if ($cloud_black_level == 4 || strpos($cloud_black_reason, '#特殊云黑#') !== false) {
        return [
            'status' => 'error',
            'message' => '不接受申诉',
        ];
    }

    return [
        'status' => 'success',
        'message' => '申诉已接收',
    ];
}

// 确保前端使用POST方式请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 检查前端以 POST 方式传递的参数
    $cloud_black_level = filter_input(INPUT_POST, 'cloud_black_level', FILTER_VALIDATE_INT);
    $cloud_black_reason = filter_input(INPUT_POST, 'cloud_black_reason', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 验证参数是否正确
    if ($cloud_black_level !== false && $cloud_black_level !== null && $cloud_black_reason !== null) {
        // 使用数据库连接对象 $db 进行查询和操作
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // 检查数据库连接是否成功
        if ($db->connect_error) {
            die(json_encode([
                'status' => 'error',
                'message' => '数据库连接失败: ' . $db->connect_error,
            ]));
        }

        // 设置数据库字符集为 utf8
        $db->set_charset("utf8");

        // 使用预处理语句来执行数据库操作，以防止SQL注入攻击
        $stmt = $db->prepare("INSERT INTO qzy_blacklist (cloud_black_level, cloud_black_reason) VALUES (?, ?)");
        $stmt->bind_param("is", $cloud_black_level, $cloud_black_reason);

        // 执行预处理语句
        if ($stmt->execute()) {
            // 调用函数检查云黑信息并获取返回结果
            $result = checkCloudBlackInfo($cloud_black_level, $cloud_black_reason);

            // 返回 JSON 格式的响应
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            // 数据库操作失败
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => '数据库操作失败',
            ]);
        }

        // 关闭预处理语句和数据库连接
        $stmt->close();
        $db->close();
    } else {
        // 参数不正确
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => '请求错误，参数不正确',
        ]);
    }
} else {
    // 不支持的请求方法
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => '方法不允许',
    ]);
}
?>
