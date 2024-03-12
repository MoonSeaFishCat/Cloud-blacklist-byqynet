<?php
require_once '../db_config.php';

// 获取申诉信息列表
function getAppeals()
{
  global $db;

  $query = "SELECT id, black_info, black_reason, appeal_reason, appeal_evidence, contact_email, created_at FROM qzy_appeal";
  $result = $db->query($query);

  $appeals = array();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $appeals[] = $row;
    }
  }

  return $appeals;
}

// 获取单个申诉信息
function getAppeal($id)
{
  global $db;

  $id = $db->real_escape_string($id);
  $query = "SELECT * FROM qzy_appeal WHERE id = '$id'";
  $result = $db->query($query);

  if ($result->num_rows > 0) {
    return $result->fetch_assoc();
  }

  return null;
}

// 删除申诉信息
function deleteAppeal($id)
{
  global $db;

  $id = $db->real_escape_string($id);
  $query = "DELETE FROM qzy_appeal WHERE id = '$id'";
  $result = $db->query($query);

  return $result;
}

// 处理请求
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // 获取申诉信息列表
  $appeals = getAppeals();

  // 返回申诉信息列表数据
  header('Content-Type: application/json');
  echo json_encode($appeals);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 删除申诉信息
  $data = json_decode(file_get_contents('php://input'), true);

  if (isset($data['id'])) {
    $id = $data['id'];
    $result = deleteAppeal($id);

    if ($result) {
      echo json_encode(array('message' => '删除成功'));
    } else {
      echo json_encode(array('message' => '删除失败'));
    }
  } else {
    echo json_encode(array('message' => '缺少参数'));
  }
}
?>
