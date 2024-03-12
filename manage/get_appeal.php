<?php
require_once '../db_config.php';

// 获取待审核列表
$query = "SELECT * FROM qzy_appeal_pending";
$result = $db->query($query);

$pendingList = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingList[] = $row;
    }
}

// 返回待审核列表数据
header('Content-Type: application/json');
echo json_encode($pendingList);
?>
