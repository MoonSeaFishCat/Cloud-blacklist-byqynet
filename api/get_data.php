<?php
require_once '../db_config.php';

// 查询管理员账户信息
$queryAdmin = "SELECT Username, Email FROM qzy_admin WHERE ID = 1";
$resultAdmin = $db->query($queryAdmin);
$admin = $resultAdmin->fetch_assoc();

// 查询网站信息
$queryConfig = "SELECT * FROM qzy_config WHERE id = 0";
$resultConfig = $db->query($queryConfig);
$config = $resultConfig->fetch_assoc();

// 隐藏密码字段
$admin['Password'] = '******';

$data = [
  'admin' => $admin,
  'config' => $config
];

// 返回数据
echo json_encode($data);

$db->close();
?>
