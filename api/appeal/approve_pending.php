<?php
require_once '../../db_config.php';

// 获取待审核ID
$id = $_POST['id'];

// 查询待审核信息
$query = "SELECT * FROM qzy_appeal_pending WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // 将待审核信息写入审核通过的数据表
    $query = "INSERT INTO qzy_appeal (black_info, black_reason, appeal_reason, appeal_evidence, contact_email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssss", $row['black_info'], $row['black_reason'], $row['appeal_reason'], $row['appeal_evidence'], $row['contact_email']);
    $stmt->execute();
    $stmt->close();

    // 从待审核数据表中删除对应信息
    $query = "DELETE FROM qzy_appeal_pending WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 返回成功消息
    echo "审核通过，数据写入成功";
} else {
    // 返回错误消息
    echo "找不到待审核信息";
}
?>
