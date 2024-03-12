<?php
include '../db_config.php';

// Create database connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the database connection is successful
if ($db->connect_error) {
  die("数据库连接失败: " . $db->connect_error);
}

// Set the database character set to utf8
$db->set_charset("utf8");

// Fetch blacklist entries
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

// Delete blacklist entry by ID
function deleteBlacklistEntry($id) {
  global $db;
  $sql = "DELETE FROM qzy_blacklist WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Fetch blacklist entries
  $blacklist = fetchBlacklist();
  echo json_encode($blacklist);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  // Delete blacklist entry
  $id = $_GET['id'];
  deleteBlacklistEntry($id);
  echo json_encode(['success' => true]);
} else {
  // Invalid request method
  http_response_code(405); // Method Not Allowed
  echo json_encode(['error' => 'Invalid request method']);
}
?>
