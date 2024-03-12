<?php
session_start();

// 导入数据库配置文件
require_once '../db_config.php';

// 设置跨域请求头
// header('Access-Control-Allow-Origin: http://your-frontend-url');
// header('Access-Control-Allow-Methods: GET, POST, DELETE');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Credentials: true');

// 处理添加管理员请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password']; // 从表单获取密码
  $bindQQ = $_POST['bind_qq'];
  $email = $_POST['email'];

  // 使用 SHA3-512 算法对密码进行哈希处理
  $hashedPassword = base64_encode(hash('sha3-512', $password, true));

  // 使用预处理语句防止SQL注入
  $insertQuery = "INSERT INTO qzy_user (Username, Password, `bind_qq`, Email) VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($insertQuery);

  $stmt->bind_param("ssss", $username, $hashedPassword, $bindQQ, $email);

  if ($stmt->execute()) {
    $response = [
      'success' => true,
      'message' => '管理员添加成功',
    ];
  } else {
    $response = [
      'success' => false,
      'message' => '管理员添加失败',
    ];
  }

  // 返回响应
  sendResponse($response);
}

// 处理删除管理员请求
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  $adminID = $_GET['adminID'];

  // 使用预处理语句防止SQL注入
  $deleteQuery = "DELETE FROM qzy_user WHERE ID = ?";
  $stmt = $db->prepare($deleteQuery);
  $stmt->bind_param("i", $adminID);

  if ($stmt->execute()) {
    $response = [
      'success' => true,
      'message' => '管理员删除成功',
    ];
  } else {
    $response = [
      'success' => false,
      'message' => '管理员删除失败',
    ];
  }

  // 返回响应
  sendResponse($response);
}

// 处理获取管理员列表请求
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // 处理搜索关键字
  if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // 使用预处理语句防止SQL注入
    $selectQuery = "SELECT * FROM qzy_user WHERE Username LIKE CONCAT('%', ?, '%')";
    $stmt = $db->prepare($selectQuery);
    $stmt->bind_param("s", $searchQuery);
  } else {
    $selectQuery = "SELECT * FROM qzy_user";
    $stmt = $db->prepare($selectQuery);
  }

  // 执行查询操作
  $stmt->execute();
  $result = $stmt->get_result();

  $admins = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $admins[] = $row;
    }
  }

  // 返回管理员列表
  sendResponse($admins);
}

// 处理重置密码请求
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  parse_str(file_get_contents("php://input"), $putData);
  $adminID = $putData['adminID'];

  // 设置新密码为 "123456"
  $newPassword = base64_encode(hash('sha3-512', '123456', true));

  // 使用预处理语句防止SQL注入
  $updateQuery = "UPDATE qzy_user SET Password = ? WHERE ID = ?";
  $stmt = $db->prepare($updateQuery);
  $stmt->bind_param("si", $newPassword, $adminID);

  if ($stmt->execute()) {
    $response = [
      'success' => true,
      'message' => '密码重置成功',
    ];
  } else {
    $response = [
      'success' => false,
      'message' => '密码重置失败',
    ];
  }

  // 返回响应
  sendResponse($response);
}

// 发送响应
function sendResponse($data) {
  header('Content-Type: application/json');
  echo json_encode($data);
  exit();
}

?>
