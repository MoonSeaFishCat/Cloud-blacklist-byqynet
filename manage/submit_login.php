<?php
session_start();

// 导入数据库配置文件
require_once '../db_config.php';

// 获取前端传递的用户名和密码
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

// 对用户名进行输入过滤和验证
if (empty($username)) {
  $response = [
    'success' => false,
    'message' => '用户名不能为空'
  ];
} else {
  // 使用预处理语句进行查询
  $query = "SELECT * FROM qzy_user WHERE Username = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();

  // 获取查询结果
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // 用户存在，验证密码
    $row = $result->fetch_assoc();
    $hashedPassword = $row['Password'];

    // 使用 SHA3-512 算法对密码进行哈希处理
    $hashedInputPassword = base64_encode(hash('sha3-512', $password, true));

    if ($hashedInputPassword === $hashedPassword) {
      // 密码验证成功，登录成功
      
      $_SESSION['logged_in'] = true;
       $_SESSION['username'] = $username; // 将登录的用户名存储在 $_SESSION 变量中
      $response = [
        'success' => true,
        'message' => '登录成功'
      ];
    } else {
      // 密码验证失败，登录失败
      $response = [
        'success' => false,
        'message' => '用户名或密码错误'
      ];
    }
  } else {
    // 用户不存在，登录失败
    $response = [
      'success' => false,
      'message' => '用户名或密码错误'
    ];
  }
}

// 返回登录结果给前端
header('Content-Type: application/json');
echo json_encode($response);
?>
