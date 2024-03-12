<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>管理员后台主页</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f7e1e8;
    }

    .header {
      background-color: #f8c1c8;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .header h2 {
      color: #fff;
      margin: 0;
      font-family: 'Arial', sans-serif;
    }

    .header-avatar {
      display: flex;
      align-items: center;
      position: relative;
    }

    .header-avatar img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-left: 10px;
      cursor: pointer;
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      min-width: 120px;
      background-color: #fff;
      padding: 10px 0;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: none;
    }

    .header-avatar:hover .dropdown-menu {
      display: block;
    }

    .dropdown-item {
      padding: 5px 20px;
      color: #333;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .dropdown-item:hover {
      background-color: #f8c1c8;
      color: #fff;
    }

    .sidebar {
      background-color: #f8c1c8;
      width: 200px;
      position: fixed;
      top: 80px;
      bottom: 0;
      left: 0;
      padding-top: 20px;
      border-right: 1px solid #e6b3bd;
    }

    .sidebar ul.nav {
      padding-left: 0;
      margin-bottom: 0;
    }

    .sidebar .nav-link {
      color: #fff;
      padding: 10px 20px;
      transition: background-color 0.3s;
      text-align: center;
      position: relative;
    }

    .sidebar .nav-link::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(to right, rgba(255, 255, 255, 0.8), rgba(248, 193, 200, 0.8), rgba(255, 255, 255, 0.8));
      z-index: -1;
      border-radius: 10px;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .sidebar .nav-link:hover::before {
      opacity: 1;
    }

    .content {
      margin-left: 200px;
      padding: 20px;
    }

    .loading {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
    }

    .loading-text {
      font-size: 24px;
      font-weight: bold;
      color: #888;
    }

    /* 二次元化风格 */
    .header h2 {
      font-size: 28px;
      color: #fff;
      font-family: 'Comic Sans MS', cursive;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .sidebar {
      background-color: #fde9f2;
      border-right: none;
    }

    .sidebar ul.nav {
      margin-bottom: 20px;
    }

    .sidebar .nav-link {
      color: #fff;
      background-color: #f8c1c8;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .sidebar .nav-link::before {
      background: linear-gradient(to right, rgba(255, 255, 255, 0.8), rgba(248, 193, 200, 0.8), rgba(255, 255, 255, 0.8));
    }

    .sidebar .nav-link:hover::before {
      opacity: 0.6;
    }

    .content {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <div class="header">
    <h2>轻忆云端黑名单后台管理系统</h2>
    <div class="header-avatar">
      <span>管理员</span>
      <img src="http://q.qlogo.cn/headimg_dl?dst_uin=3544156834&spec=640&img_type=jpg" alt="QQ头像">
      <div class="dropdown-menu">
        <div class="dropdown-item">个人中心</div>
        <div class="dropdown-item">系统配置</div>
        <div class="dropdown-item">检查更新</div>
        <div class="dropdown-item">退出系统</div>
      </div>
    </div>
  </div>
  <div class="sidebar">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="Personal Center.php">个人中心</a>
      </li>
    
 
      <li class="nav-item">
        <a class="nav-link" href="cloud-blacklist-sh.php">云黑审核</a>
      </li>
    
      <li class="nav-item">
        <a class="nav-link" href="appeal-sh.php">申诉处理</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">退出系统</a>
      </li>
    </ul>
  </div>
</body>
</html>
