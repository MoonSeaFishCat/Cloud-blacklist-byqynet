<?php
session_start();

// 导入数据库配置文件
require_once '../db_config.php';

// 检查用户是否已登录
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  $response = [
    'success' => false,
    'message' => '用户未登录'
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}

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

// 返回响应给前端
header('Content-Type: application/json');
echo json_encode($response);
?>
