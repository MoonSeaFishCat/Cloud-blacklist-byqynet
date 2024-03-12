<?php
include('../db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 处理管理员账户设置
  if (isset($_POST['username'])) {
    $username = $_POST['username'];
    // 执行更新数据库操作
    $query = "UPDATE qzy_admin SET Username='$username'";
    $result = $db->query($query);
    if ($result) {
      echo "用户名更新成功";
    } else {
      echo "用户名更新失败";
    }
  }

  if (isset($_POST['password'])) {
    $password = $_POST['password'];
    // 使用sha3-512和base64进行加密
    $hashedPassword = base64_encode(hash('sha3-512', $password));
    // 执行更新数据库操作
    $query = "UPDATE qzy_admin SET Password='$hashedPassword'";
    $result = $db->query($query);
    if ($result) {
      echo "密码更新成功";
    } else {
      echo "密码更新失败";
    }
  }

  if (isset($_POST['email'])) {
    $email = $_POST['email'];
    // 执行更新数据库操作
    $query = "UPDATE qzy_admin SET Email='$email'";
    $result = $db->query($query);
    if ($result) {
      echo "邮箱更新成功";
    } else {
      echo "邮箱更新失败";
    }
  }

  // 处理网站信息
  if (isset($_POST['siteName'])) {
    $siteName = $_POST['siteName'];
    // 执行更新数据库操作
    $query = "UPDATE qzy_config SET `Site Name`='$siteName' WHERE id=0";
    $result = $db->query($query);
    if ($result) {
      echo "网站名称更新成功";
    } else {
      echo "网站名称更新失败";
    }
  }

  if (isset($_POST['copyright'])) {
    $copyright = $_POST['copyright'];
    // 执行更新数据库操作
    $query = "UPDATE qzy_config SET `Copyright Notice`='$copyright' WHERE id=0";
    $result = $db->query($query);
    if ($result) {
      echo "版权声明更新成功";
    } else {
      echo "版权声明更新失败";
    }
  }

  if (isset($_POST['contact'])) {
    $contact = $_POST['contact'];
    // 执行更新数据库操作
    $query = "UPDATE qzy_config SET `contact information`='$contact' WHERE id=0";
    $result = $db->query($query);
    if ($result) {
      echo "联系方式更新成功";
    } else {
      echo "联系方式更新失败";
    }
  }
}
?>
