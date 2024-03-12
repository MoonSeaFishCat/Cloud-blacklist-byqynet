<?php
require('../assets/auth.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>登录界面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  
  <!-- 引入bootstrap样式表 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  
  <style>
    body {
      background-color: #e0e5ec;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .login-box {
      background-color: #e0e5ec;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
      text-align: center;
      width: 100%;
      max-width: 400px;
    }

    .form-control {
      background-color: #e0e5ec;
      border: none;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
      margin-bottom: 20px;
      padding: 15px;
      border-radius: 10px;
    }

    .form-control:focus {
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
    }

    .login-button {
      background-color: #e0e5ec;
      color: #333;
      border: none;
      box-shadow: 7px 7px 15px #bbcfda, -4px -4px 13px #ffffff;
      padding: 15px;
      border-radius: 10px;
      font-size: 18px;
      font-weight: bold;
    }

    .login-button:hover {
      background-color: #d1d9e6;
    }

    .login-button:active {
      background-color: #f2f2f2;
      box-shadow: 4px 2px 10px #bbcfda, -4px -4px 13 #ffffff;
    }

    @media only screen and (max-width: 600px) {
      .login-box {
        width: 90%;
      }
    }
  </style>
  
  <!-- 引入vue脚本 -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <!-- 引入sweetalrt2脚本和样式表 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.5/sweetalert2.min.css">
</head>
<body>
  <div id="app">
    <div class="login-box">
        <h2>后台登录</h2>
      <form @submit.prevent="login">
        <div class="user-box">
         <label>邮箱</label> <input type="email" v-model="email" required placeholder="请输入邮箱.." class="form-control">
          
        </div>
        <div class="user-box">
          <label>密码</label><input type="password" v-model="password" required placeholder="请输入密码.." class="form-control">
          
        </div>
        <button type="submit" class="login-button btn btn-primary" :disabled="loginStatus">登录</button>
      </form>
    </div>
  </div>
  <script>
    new Vue({
      el: '#app',
      data() {
        return {
          email: '', // 邮箱
          password: '', // 密码
          loginStatus: false // 登录状态
        };
      },
      methods: {
        login() {
          if (this.loginStatus) {
            return;
          }
          this.loginStatus = true;
          // 显示加载提示框
          Swal.fire({
            title: '正在登录...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // 构建请求的数据
          const formData = new FormData();
          formData.append('email', this.email);
          formData.append('password', this.password);

          // 发送登录请求
          fetch('submit_login.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // 登录成功
              Swal.fire({
                icon: 'success',
                title: '登录成功',
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                // 执行登录成功后的操作
                console.log('登录成功');
                window.location.href = "data-center.php"
              });
            } else {
              // 登录失败
              Swal.fire({
                icon: 'error',
                title: '登录失败',
                text: data.message
              }).then(() => {
                // 执行登录失败后的操作
                console.log('登录失败');
              });
            }
            this.loginStatus = false;
          })
          .catch(error => {
            console.error('登录请求出错:', error);
            this.loginStatus = false;
          });
        }
      }
    });
  </script>
</body>
</html>
