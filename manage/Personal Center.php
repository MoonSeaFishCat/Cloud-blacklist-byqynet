<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require('home.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>个人中心</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    /* 二次元样式 */
    body {
      background-color: #FCEFF4;
    }
    .container {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .personal-card {
      max-width: 500px;
      padding: 20px;
      border-radius: 10px;
      background-color: #FFF8F7;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .avatar {
      width: 150px;
      height: 150px;
      border: 5px solid #FF85A2;
      border-radius: 50%;
      object-fit: cover;
    }
    .admin-info {
      margin-top: 50px;
      text-align: center;
      position: relative;
      padding: 20px;
      border-radius: 10px;
      border: 2px solid #FF85A2;
      box-shadow: 0 0 10px #FF85A2;
      animation: border-glow 2s infinite linear;
    }
    .admin-info p {
      color: #FF85A2;
      font-size: 16px;
    }
    @keyframes border-glow {
      0% {
        box-shadow: 0 0 10px #FF85A2;
      }
      50% {
        box-shadow: 0 0 20px #FF85A2;
      }
      100% {
        box-shadow: 0 0 10px #FF85A2;
      }
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="container">
      <div class="personal-card">
        <div class="text-center">
          <img :src="avatarURL" class="avatar" alt="Avatar">
        </div>
          <div class="admin-info">
          <p><b>当前用户权限</b></p>
          <p>二级管理员</p>
        </div>
        <div class="admin-info">
          <p>Welcome, {{ username }}</p>
          <p>管理员须知:</p>
          <p>1.请公正地进行审核任务，如果发现任何违规行为，将被撤职，严重的会被加入四级云黑</p>
          <p>2.禁止使用自己的qq号联系上传者或被云黑人员，进行收费解黑行为，违者撤职并加入四级云黑。造成严重后果的有可能会公开所有信息！</p>
          <p>3.只允许进行三级及之下的云黑申诉，四级云黑请直接驳回</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Vue.js -->
  <script src="https://unpkg.com/vue@2.6.14/dist/vue.min.js"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        username: '',
        bind_qq: ''
      },
      created() {
        // 页面加载时获取登录信息
        this.getLoginInfo();
      },
      computed: {
        avatarURL() {
          // 返回根据QQ号码动态获取头像的URL
          return `http://q.qlogo.cn/headimg_dl?dst_uin=${this.bind_qq}&spec=640&img_type=jpg`;
        }
      },
      methods: {
        getLoginInfo() {
          // 发送请求获取登录信息
          fetch('get-data.php')
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // 登录成功，更新数据
                this.username = data.username;
                this.bind_qq = data.bind_qq;
              } else {
                // 登录失败，处理错误
                console.error(data.message);
              }
            })
            .catch(error => {
              console.error(error);
            });
        }
      }
    });
  </script>
</body>
</html>
