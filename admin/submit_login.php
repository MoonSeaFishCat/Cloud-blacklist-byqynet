<?php
session_start();

// 引入数据库配置文件
require_once '../db_config.php';

// 获取前端传递的邮箱和密码
$email = $_POST['email'];
$password = $_POST['password'];

// 进行输入验证和过滤
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// 对密码进行加密处理
$hashedPassword = base64_encode(hash('sha3-512', $password, true));

// 使用预处理语句进行查询
$query = "SELECT * FROM qzy_admin WHERE Email = ? AND Password = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $email, $hashedPassword);
$stmt->execute();

// 获取查询结果
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // 登录成功
  $_SESSION['logged_in'] = true;
  $_SESSION['username'] = $email;
  $response = [
    'success' => true,
    'message' => '登录成功'
  ];
} else {
  // 登录失败
  $response = [
    'success' => false,
    'message' => '邮箱或密码错误'
  ];
}

// 返回登录结果给前端
header('Content-Type: application/json');
echo json_encode($response);
?>
