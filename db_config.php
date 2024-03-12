<?php
// 定义数据库参数
define("DB_HOST", "localhost"); // 数据库地址
define("DB_USER", "yh"); // 用户名
define("DB_NAME", "yh"); // 数据库名
define("DB_PASS", "bcWpfHRGe7RznyDk"); // 密码

// 创建数据库连接对象
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 检查数据库连接是否成功
if ($db->connect_error) {
  die("数据库连接失败: " . $db->connect_error);
}

// 设置数据库字符集为 utf8
$db->set_charset("utf8");


?>
