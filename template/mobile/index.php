<?php
// 计算本站累计运行时间
function getRunningTime() {
  $startDate = strtotime('2023-07-14'); // 您的网站开始运行的日期的时间戳
  $now = time();
  $diff = $now - $startDate;
  $days = floor($diff / (60 * 60 * 24));
  return $days . '天';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>轻忆の黑名单系统</title>
  <link rel="stylesheet" href="./include/bootstrap.min.css">
  <style>
    body {
      background-color: #e0e5ec;
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      position: relative;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .navbar {
      background-color: #e0e5ec;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
      border: none;
    }

    .navbar-brand {
      font-family: 'Comic Sans MS', cursive;
      font-size: 28px;
      color: #333;
    }

    .card-deck {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin: 20px 0;
    }

    .card {
      background-color: #e0e5ec;
      border-radius: 10px;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
      margin: 20px 10px;
      flex: 0 0 auto;
      width: 800px;
    }

    .card img {
      border-radius: 10px 10px 0 0;
      object-fit: cover;
      height: 250px;
    }

    .card-content {
      padding: 20px;
    }

    .intro-buttons {
      margin-top: 20px;
      display: flex;
      justify-content: space-evenly;
    }

    .footer {
      background-color: #e0e5ec;
      color: #333;
      padding: 20px 0;
      width: 100%;
      text-align: center;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
    }

    .contact-us-card,
    .links-card {
      background-color: #e0e5ec;
      border-radius: 10px;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
      margin: 20px 10px;
      flex: 0 0 auto;
      max-width: calc(50% - 20px);
      width: 600px;
    }

    .contact-us-card .card-content,
    .links-card .card-content {
      padding: 20px;
    }

    .btn {
      background-color: #e0e5ec;
      color: #333;
      border: none;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
    }

    .btn:hover {
      background-color: #d1d9e6;
    }

    .btn:active {
      background-color: #f2f2f2;
      box-shadow: 4px 2px 10px #bbcfda, -4px -4px 13px #ffffff;
    }

    .sakura-container {
      display: none;
    }
        @media (max-width: 991px) {
      .card, .contact-us-card, .links-card {
        width: 100%;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="#">轻忆云端黑名单系统</a>
      </div>
    </nav>

    <div class="card-deck">
      <div class="card">
        <img src="/assets/image/sybj/1.jpg" class="card-img-top" alt="Slide 1">
        <div class="card-content">
          <h2 class="text-center">轻忆云端黑名单系统</h2>
          <p>骗子一旦上榜，无处藏身</p>
          <p>目前正在试运行阶段。如果一切顺利，一周内正式上线。</p>
          <div class="intro-buttons">
      <a class="btn waves-effect waves-light pink darken-2" href="/template/mobile/appeal/Notice.php">我要申诉</a>
      
      <a class="btn waves-effect waves-light pink darken-2" href="/template/mobile/Inquire/index.php" >我要查询</a>
      <a class="btn waves-effect waves-light pink darken-2" href="/template/mobile/report/Notice.php">举报上黑</a>
          </div>
        </div>
      </div>
      <!-- Add more card items as needed -->
    </div>

    <div class="card-deck">
      <div class="contact-us-card">
        <div class="card-content">
          <h3 class="text-center">联系我们</h3>
          <p class="text-center">如果您有任何问题或建议，欢迎随时与我们联系。</p>
          <p class="text-center">Email:isqynet@outlook.com</p>
        </div>
      </div>

      <div class="links-card">
        <div class="card-content">
          <h3 class="text-center">友情链接</h3>
          <p class="text-center"><a href="https://xz.xkfaka.com">轻忆资源天堂</a></p>
          <p class="text-center"><a href="https://xkfaka.com">星空商城</a></p>
          <!-- Add more links as needed -->
        </div>
      </div>
    </div>

    <footer class="text-center footer">
      <div class="container">
         <p>京ICP备05060933号</p>
         <p>&copy; 2023-<?php echo date('Y'); ?> 云端黑名单系统 | 运行时间：<?php echo getRunningTime(); ?></p>
      </div>
    </footer>
  </div>

  <script src="./include/vue.global.js"></script>
  <script src="./include/sweetalert2.js"></script>
  <script src="./include/bootstrap.bundle.min.js"></script>
</body>
</html>
