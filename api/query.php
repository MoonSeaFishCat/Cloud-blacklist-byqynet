<?php
header('Content-Type: application/json; charset=utf-8');

// 引入数据库配置文件
require_once '../db_config.php';

// 验证请求方法
$allowedMethods = ['GET', 'POST'];
$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, $allowedMethods)) {
    http_response_code(405);
    echo json_encode(['status' => 405, 'success' => false, 'message' => '请求方法不被允许'], JSON_UNESCAPED_UNICODE);
    exit();
}

// 获取请求数据
if ($method === 'POST') {
    $requestData = $_POST;
} else { // GET 请求
    $requestData = $_GET;
}

// 验证必填字段
if (!isset($requestData['data'])) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'success' => false, 'message' => '必填字段不能为空'], JSON_UNESCAPED_UNICODE);
    exit();
}

// 过滤用户输入
$data = sanitizeInput($requestData['data']);

// 在此处执行查询操作
// 这里只是一个示例
// 你需要根据实际需求修改此部分的代码

// 使用预处理语句进行查询
$sql = "SELECT * FROM qzy_blacklist WHERE CONCAT('#', cloud_black_info, '#') LIKE CONCAT('%#', ?, '#%')";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $data);
$stmt->execute();
$result = $stmt->get_result();

// 检查查询结果
if ($result === false) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'success' => false, 'message' => '查询失败'], JSON_UNESCAPED_UNICODE);
    exit();
}

// 解析查询结果
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

// 返回查询结果或未查询到信息的消息
if (empty($rows)) {
    http_response_code(400); // Set status code to 400 for not found
    $message = '未查询到该信息，请注意交易安全';
    echo json_encode(['status' => 400, 'success' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(200); // Set status code to 200 for success
    echo json_encode(['status' => 200, 'success' => true, 'data' => $rows], JSON_UNESCAPED_UNICODE);
}

/**
 * 过滤和转义用户输入
 * 
 * @param string $input 用户输入
 * @return string
 */
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
?>
