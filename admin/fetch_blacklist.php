<?php
include '../db_config.php';

// 创建数据库连接
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 检查数据库连接是否成功
if ($db->connect_error) {
    die("数据库连接失败: " . $db->connect_error);
}

// 设置数据库字符集为utf8
$db->set_charset("utf8");

// 获取黑名单条目
function fetchBlacklist() {
    global $db;
    $sql = "SELECT * FROM qzy_blacklist";
    $result = $db->query($sql);
    $blacklist = [];
    while ($row = $result->fetch_assoc()) {
        $blacklist[] = $row;
    }
    return $blacklist;
}

// 通过ID删除黑名单条目
function deleteBlacklistEntry($id) {
    global $db;
    $sql = "SELECT image_paths FROM qzy_blacklist WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePaths);
    $stmt->fetch();
    $stmt->close();

    // 删除相关文件
    $imagePaths = explode('#', $imagePaths);
    foreach ($imagePaths as $imagePath) {
        $filePath = __DIR__ . '/../upload/' . $imagePath;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // 从数据库中删除该条目
    $sql = "DELETE FROM qzy_blacklist WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// 处理API请求
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 获取黑名单条目
    $blacklist = fetchBlacklist();
    echo json_encode($blacklist);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 删除黑名单条目
    $id = $_GET['id'];
    deleteBlacklistEntry($id);
    echo json_encode(['success' => true]);
} else {
    // 无效的请求方法
    http_response_code(405); // 方法不允许
    echo json_encode(['error' => '无效的请求方法']);
}
?>
