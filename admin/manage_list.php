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
  <title>管理员列表</title>
  <!-- 引入Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- 引入SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
  <!-- 引入自定义样式 -->
  <style>
    body {
      background-color: #FFD8E2;
      /* 白桃粉背景色 */
    }

    /* 添加二次元化样式 */
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #F5F5F5;
      /* 背景色 */
      border-radius: 10px;
    }

    h1 {
      color: #FF69B4;
      /* 主色调，粉红色 */
      text-align: center;
      margin-bottom: 20px;
      font-size: 36px;
    }

    .form-control {
      margin-bottom: 10px;
    }

    .btn {
      color: white;
    }

    .btn-primary {
      background-color: #FF69B4;
      /* 主色调，粉红色 */
      border-color: #FF69B4;
      /* 主色调，粉红色 */
    }

    .btn-success {
      background-color: #A020F0;
      /* 按钮颜色，紫色 */
      border-color: #A020F0;
      /* 按钮颜色，紫色 */
    }

    .btn-danger {
      background-color: #FF4500;
      /* 按钮颜色，橙红色 */
      border-color: #FF4500;
      /* 按钮颜色，橙红色 */
    }

    .table {
      background-color: white;
    }
  </style>
</head>

<body>
  <div id="app" class="container mt-5">
    <h1>管理员列表</h1>
    <span>添加管理员之后有十秒左右的缓存期，解决不了，先将就着用吧！</span>
    <!-- 搜索框 -->
    <div class="mb-3">
      <input type="text" class="form-control" v-model="searchQuery" placeholder="搜索用户名">
      <button class="btn btn-primary mt-2" @click="searchAdmin">搜索</button>
    </div>
    <!-- 添加管理员按钮 -->
    <button class="btn btn-success mb-3" @click="showAddAdminModal">添加管理员</button>
    <!-- 管理员列表表格 -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>用户名</th>
          <th>绑定QQ</th>
          <th>邮箱</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="admin in filteredAdmins" :key="admin.ID">
          <td>{{ admin.Username }}</td>
          <td>{{ admin.bind_qq }}</td>
          <td>{{ admin.Email }}</td>
          <td>
            <button class="btn btn-danger" @click="deleteAdmin(admin.ID)">删除</button>
            <button class="btn btn-primary" @click="resetPassword(admin.ID)">重置密码</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- 引入Vue.js -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <!-- 引入Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <!-- 引入SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
  <!-- 自定义脚本 -->
  <script>
    new Vue({
      el: "#app",
      data() {
        return {
          admins: [], // 管理员列表数据
          searchQuery: '', // 搜索关键字
        }
      },
      mounted() {
        this.fetchAdmins(); // 页面加载时获取管理员列表数据
      },
      methods: {
        // 获取管理员列表数据
        fetchAdmins() {
          axios.get('manage_api.php', {
              params: {
                search: this.searchQuery
              },
              withCredentials: true
            })
            .then(response => {
              this.admins = response.data;
            })
            .catch(error => {
              console.error(error);
              Swal.fire('获取管理员列表失败', '请稍后重试', 'error');
            });
        },

        // 添加管理员
        showAddAdminModal() {
          Swal.fire({
            title: '添加管理员',
            html: '<input id="username" class="swal2-input" placeholder="用户名">' +
              '<input id="bind_qq" class="swal2-input" placeholder="绑定QQ">' +
              '<input id="email" class="swal2-input" placeholder="邮箱">' +
              '<input type="password" id="password" class="swal2-input" placeholder="密码">',
            showCancelButton: true,
            confirmButtonText: '添加',
            showLoaderOnConfirm: true,
            preConfirm: () => {
              const formData = new FormData();
              formData.append('username', document.getElementById('username').value);
              formData.append('bind_qq', document.getElementById('bind_qq').value);
              formData.append('email', document.getElementById('email').value);
              formData.append('password', document.getElementById('password').value);

              return axios.post('manage_api.php', formData, {
                  withCredentials: true
                })
                .then(response => {
                  const data = response.data;
                  if (data.success) {
                    this.fetchAdmins(); // 添加成功后刷新管理员列表
                    Swal.fire('添加管理员成功', '', 'success');
                  } else {
                    throw new Error(data.message);
                  }
                })
                .catch(error => {
                  console.error(error);
                  Swal.fire('添加管理员失败', '请稍后重试', 'error');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          });
        },

        // 删除管理员
        deleteAdmin(adminID) {
          Swal.fire({
            title: '确定要删除该管理员吗？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '删除',
            showLoaderOnConfirm: true,
            preConfirm: () => {
              return axios.delete('manage_api.php', {
                  params: {
                    adminID: adminID
                  },
                  withCredentials: true
                })
                .then(response => {
                  const data = response.data;
                  if (data.success) {
                    this.fetchAdmins(); // 删除成功后刷新管理员列表
                    Swal.fire('删除管理员成功', '', 'success');
                  } else {
                    throw new Error(data.message);
                  }
                })
                .catch(error => {
                  console.error(error);
                  Swal.fire('删除管理员失败', '请稍后重试', 'error');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          });
        },

        // 重置密码
        resetPassword(adminID) {
          Swal.fire({
            title: '确定要重置该管理员的密码吗？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '重置',
            showLoaderOnConfirm: true,
            preConfirm: () => {
              return axios.put('manage_api.php', {
                  adminID: adminID
                }, {
                  withCredentials: true
                })
                .then(response => {
                  const data = response.data;
                  if (data.success) {
                    Swal.fire('重置密码成功', '新密码为: 123456', 'success');
                  } else {
                    throw new Error(data.message);
                  }
                })
                .catch(error => {
                  console.error(error);
                  Swal.fire('重置密码失败', '请稍后重试', 'error');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
          });
        },

        // 搜索管理员
        searchAdmin() {
          this.fetchAdmins();
        }
      },
      computed: {
        // 根据搜索关键字过滤管理员列表
        filteredAdmins() {
          return this.admins.filter(admin => admin.Username.toLowerCase().includes(this.searchQuery.toLowerCase()));
        }
      }
    });
  </script>
</body>

</html>
