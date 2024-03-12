<?php
// 导入数据库配置文件
require_once '../db_config.php';

// 定义返回结果的数据结构
$response = array(
  'cloudBlacklistCount' => 0,
  'pendingCloudBlacklistCount' => 0,
  'appealCount' => 0,
  'pendingAppealCount' => 0,
  'adminCount' => 0
);

// 查询云黑信息数
$sql = "SELECT COUNT(*) as count FROM qzy_blacklist";
$result = $db->query($sql);
if ($result) {
  $row = $result->fetch_assoc();
  $response['cloudBlacklistCount'] = $row['count'];
}

// 查询云黑待处理数
$sql = "SELECT COUNT(*) as count FROM qzy_blacklist_pending";
$result = $db->query($sql);
if ($result) {
  $row = $result->fetch_assoc();
  $response['pendingCloudBlacklistCount'] = $row['count'];
}

// 查询申诉信息数
$sql = "SELECT COUNT(*) as count FROM qzy_appeal";
$result = $db->query($sql);
if ($result) {
  $row = $result->fetch_assoc();
  $response['appealCount'] = $row['count'];
}

// 查询申诉待处理数
$sql = "SELECT COUNT(*) as count FROM qzy_appeal_pending";
$result = $db->query($sql);
if ($result) {
  $row = $result->fetch_assoc();
  $response['pendingAppealCount'] = $row['count'];
}

// 查询管理员数（user表）
$sql = "SELECT COUNT(*) as count FROM qzy_user";
$result = $db->query($sql);
if ($result) {
  $row = $result->fetch_assoc();
  $response['adminCount'] = $row['count'];
}

// 返回结果
header('Content-Type: application/json');
echo json_encode($response);
?>
