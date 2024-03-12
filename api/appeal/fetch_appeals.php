<?php
include '../../db_config.php';

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
    $blacklist = [];

    $sql = "SELECT * FROM qzy_appeal"; // Fixed table name
    $result = $db->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row)) { // Only append non-empty rows
                $blacklist[] = $row;
            }
        }
    }

    return $blacklist;
}

// Delete blacklist entry by ID
function deleteBlacklistEntry($id) {
    global $db;
    $sql = "DELETE FROM qzy_appeal WHERE id = ?"; // Fixed table name
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle API requests
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch blacklist entries
    $blacklist = fetchBlacklist();
    if (!empty($blacklist)) {
        $response = $blacklist;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Ensure ID is set and is numeric
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
        deleteBlacklistEntry($id);
        $response = ['success' => true];
    } else {
        http_response_code(400); // Bad Request
        $response = ['error' => 'Invalid ID'];
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    $response = ['error' => 'Invalid request method'];
}

// Only echo non-empty responses
if (!empty($response)) {
    echo json_encode($response);
}
?>
