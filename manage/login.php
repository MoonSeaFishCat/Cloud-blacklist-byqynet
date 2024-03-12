<?php
require('../assets/auth.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>管理员登录界面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>

  <!-- 引入bootstrap样式表 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- 引入vue脚本 -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <!-- 引入sweetalrt2脚本和样式表 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.5/sweetalert2.min.css">
  <!-- 引入自定义样式 -->
  <style>
    body {
      background-color: #FFD8DB; /* 白桃粉 */
      background-image: url("http://www.98qy.com/sjbz/api.php");
      background-size: cover;
    }
    .container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      width: 100%;
      max-width: 360px;
      padding: 40px 30px;
      background-color: #FFF;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .login-box h2 {
      margin-bottom: 30px;
      color: #000;
    }
    .user-box {
      position: relative;
      margin-bottom: 30px;
    }
    .user-box input {
      width: 100%;
      padding: 10px 0;
      font-size: 16px;
      color: #000;
      margin-bottom: 10px;
      border: none;
      border-bottom: 1px solid #000;
      outline: none;
      background: transparent;
    }
    .user-box label {
      position: absolute;
      top: 0;
      left: 0;
      padding: 10px 0;
      font-size: 16px;
      color: #000;
      pointer-events: none;
      transition: 0.5s;
    }
    .user-box input:focus ~ label,
    .user-box input:valid ~ label {
      top: -20px;
      font-size: 12px;
      color: #F06D90; /* 粉红色 */
    }
    .login-button {
      display: block;
      width: 100%;
      padding: 15px;
      border: none;
      background-color: #F06D90; /* 粉红色 */
      color: #FFF;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      transition: 0.3s;
    }
    .login-button:hover {
      background-color: #D4416B; /* 粉红色 */
    }
    .login-button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <div id="app">
    <div class="container">
      <div class="login-box">
        <h2>管理员后台登录</h2>
        <form @submit.prevent="login">
          <div class="user-box">
            <input type="text" v-model="username"  class="form-control">
            <label>用户名</label>
          </div>
          <div class="user-box">
            <input type="password" v-model="password"  class="form-control">
            <label>密码</label>
          </div>
          <button type="submit" class="login-button btn btn-primary" :disabled="loginStatus">登录</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    new Vue({
      el: '#app',
      data() {
        return {
          username: '', // 用户名
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
          formData.append('username', this.username);
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
                window.location.href = "Personal Center.php"
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
