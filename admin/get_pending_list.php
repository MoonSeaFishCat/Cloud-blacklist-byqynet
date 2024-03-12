<?php
// get_pending_list.php

// 导入数据库配置
require_once('../db_config.php');

// 查询待审核信息列表
$query = "SELECT * FROM qzy_blacklist_pending";
$result = $db->query($query);

if ($result->num_rows > 0) {
  $rows = array();
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }
  echo json_encode($rows);
} else {
  echo json_encode([]);
}

$db->close();
?>
