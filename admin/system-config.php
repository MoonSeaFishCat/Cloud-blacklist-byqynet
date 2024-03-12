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
  <title>系统配置</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f9e8ea; /* 设置背景颜色为 #f9e8ea */
      padding: 20px;
    }

    .half-width {
      max-width: 50%; /* 将最大宽度设置为原来的一半 */
      margin: 0 auto;
    }

    .edit-button {
      margin-left: 10px;
    }

    .announcement-toolbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-bottom: 10px;
    }

    .announcement-toolbar button {
      margin-left: 10px;
    }
  </style>
</head>

<body>
  <div class="container half-width">
    <hr>
    <h2>管理员账户设置</h2>
    <form id="adminForm">
      <div class="mb-3">
        <label for="username" class="form-label">用户名</label>
        <div class="input-group">
          <input type="text" class="form-control" id="username" name="username" value="">
          <button class="btn btn-primary edit-button">修改</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">密码</label>
        <div class="input-group">
          <input type="password" class="form-control" id="password" name="password" value="">
          <button class="btn btn-primary edit-button">修改</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">邮箱</label>
        <div class="input-group">
          <input type="email" class="form-control" id="email" name="email" value="">
          <button class="btn btn-primary edit-button">修改</button>
        </div>
      </div>
    </form>

    <hr>

    <h2>网站信息</h2>
    <form id="siteForm">
      <div class="mb-3">
        <label for="siteName" class="form-label">网站名称</label>
        <div class="input-group">
          <input type="text" class="form-control" id="siteName" name="siteName" value="">
          <button class="btn btn-primary edit-button">修改</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="copyright" class="form-label">版权声明</label>
        <div class="input-group">
          <textarea class="form-control" id="copyright" name="copyright" rows="3"></textarea>
          <button class="btn btn-primary edit-button">修改</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="contact" class="form-label">联系方式</label>
        <div class="input-group">
          <input type="text" class="form-control" id="contact" name="contact" value="">
          <button class="btn btn-primary edit-button">修改</button>
        </div>
      </div>
    </form>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.js"></script>

  <script>
    $(document).ready(function() {
      // 发送GET请求获取默认值
      $.ajax({
        url: '../api/get_data.php',
        type: 'GET',
        success: function(response) {
          var data = JSON.parse(response);

          // 填充管理员账户设置表单
          $('#username').val(data.admin.Username);
          $('#password').val('');
          $('#email').val(data.admin.Email);

          // 填充网站信息表单
          $('#siteName').val(data.config['Site Name']);
          $('#copyright').val(data.config['Copyright Notice']);
          $('#contact').val(data.config['contact information']);
        },
        error: function(xhr, status, error) {
          // 显示错误消息
          Swal.fire({
            icon: 'error',
            title: '出错了',
            text: '无法获取数据'
          });
        }
      });

      // 监听修改按钮点击事件
      $('.edit-button').click(function(e) {
        e.preventDefault();

        var formId = $(this).closest('form').attr('id');
        var formData = $('#' + formId).serialize();

        // 发送POST请求保存修改
        $.ajax({
          url: '../api/save_change.php',
          type: 'POST',
          data: formData,
          success: function(response) {
            // 显示成功消息
            Swal.fire({
              icon: 'success',
              title: '成功',
              text: '修改成功'
            });
          },
          error: function(xhr, status, error) {
            // 显示错误消息
            Swal.fire({
              icon: 'error',
              title: '出错了',
              text: '保存修改失败'
            });
          }
        });
      });
    });
  </script>
</body>
</html>
