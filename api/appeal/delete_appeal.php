<?php
require_once '../../db_config.php';

// 获取要删除的申诉信息ID
$id = $_POST['id'];

// 删除申诉信息
$query = "DELETE FROM qzy_appeal WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "success";
} else {
  echo "error";
}

$stmt->close();
?>
