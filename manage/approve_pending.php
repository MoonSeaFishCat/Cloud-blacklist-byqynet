<?php
// 导入数据库配置
require_once('../db_config.php');

// 获取请求参数
$id = $_POST['id'];

// 验证和过滤请求参数，避免SQL注入攻击

// 查询待审核信息的详细数据
$query = "SELECT * FROM qzy_blacklist_pending WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();

  // 将待审核信息写入云黑信息表
  $cloudBlackInfo = $row['cloud_black_info'];
  $cloudBlackReason = $row['cloud_black_reason'];
  $scammedAmount = $row['scammed_amount'];
  $contactEmail = $row['contact_email'];
  $imagePaths = $row['image_paths'];
  $cloudBlackLevel = isset($_POST['cloud_black_level']) ? $_POST['cloud_black_level'] : 0; // 设置默认值为0

  // 执行插入操作，并使用预处理语句进行参数绑定
  $insertQuery = "INSERT INTO qzy_blacklist (cloud_black_info, cloud_black_reason, scammed_amount, contact_email, image_paths, cloud_black_level) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $db->prepare($insertQuery);
  $stmt->bind_param("sssssi", $cloudBlackInfo, $cloudBlackReason, $scammedAmount, $contactEmail, $imagePaths, $cloudBlackLevel);
  $insertResult = $stmt->execute();

  if ($insertResult) {
    // 删除待审核信息
    $deleteQuery = "DELETE FROM qzy_blacklist_pending WHERE id = ?";
    $stmt = $db->prepare($deleteQuery);
    $stmt->bind_param("s", $id);
    $deleteResult = $stmt->execute();

    if ($deleteResult) {
      echo "审核通过，数据写入成功";
    } else {
      echo "审核通过，数据写入成功，但删除待审核信息失败";
    }
  } else {
    echo "审核通过，数据写入失败";
  }
} else {
  echo "未找到对应的待审核信息";
}

$stmt->close();
$db->close();
?>