<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require('home.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>云黑审核系统</title>
  <!-- 引入最新版本的 Bootstrap 和 Vue.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
  <style>
    /* 添加自定义样式 */
    body {
      background-color: #FEE6F0;
    }

    .custom-container {
      max-width: 50%;
    }

    .card {
      border-radius: 20px;
    }

    .btn-primary {
      background-color: #FBB2C8;
      border-color: #FBB2C8;
    }

    .btn-primary:hover {
      background-color: #EC6E96;
      border-color: #EC6E96;
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.25rem rgba(251, 178, 200, 0.5);
    }

    .table {
      background-color: #FFF2F7;
    }

    /* 弹窗样式 */
    .modal-dialog {
      max-width: 600px;
    }

    .modal-content {
      border-radius: 20px;
    }

    .modal-header {
      background-color: #FBB2C8;
      color: #fff;
      border-radius: 20px 20px 0 0;
    }

    .modal-title {
      color: #fff;
      font-weight: bold;
    }

    .modal-body {
      background-color: #FFF2F7;
      padding: 20px;
    }

    .modal-footer {
      background-color: #FFF2F7;
      border-radius: 0 0 20px 20px;
    }

    .modal-body p {
      margin-bottom: 10px;
    }

    .modal-body label {
      font-weight: bold;
    }

    .modal-body img {
      max-width: 100%;
      margin-bottom: 10px;
      border-radius: 5px;
    }

    .modal-body select {
      margin-top: 10px;
      width: 100%;
      padding: 5px;
      border-radius: 5px;
    }

    .modal-body textarea {
      margin-top: 10px;
      width: 100%;
      padding: 5px;
      border-radius: 5px;
    }

    /* 提示弹窗样式 */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }

    .toast {
      border-radius: 10px;
      background-color: #FBB2C8;
      color: #fff;
    }

    .toast-header {
      background-color: #FBB2C8;
      color: #fff;
      border-bottom: none;
    }

    .toast-header .btn-close {
      color: #fff;
    }

    .toast-body {
      background-color: #FFF2F7;
      color: #000;
    }
  </style>
</head>
<body>
  <div id="app" class="container pt-5 custom-container">
    <div class="card">
      <div class="card-header bg-white">
        <h4 class="text-center">待审核列表</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive text-center">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>云黑信息</th>
                <th>云黑原因</th>
                <th>金额</th>
                <th>Email</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in pendingList" :key="item.id">
                <td>{{ item.id }}</td>
                <td>{{ item.cloud_black_info }}</td>
                <td>{{ item.cloud_black_reason }}</td>
                <td>{{ item.scammed_amount }}</td>
                <td>{{ item.contact_email }}</td>
                <td>
                  <button class="btn btn-primary btn-sm" @click="showDetails(item)">查看详情</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- 详情模态框 -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailsModalLabel">审核详情</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <p><strong>ID:</strong> {{ selectedInfo.id }}</p>
                <p><strong>云黑信息:</strong> {{ selectedInfo.cloud_black_info }}</p>
                <p><strong>云黑原因:</strong> {{ selectedInfo.cloud_black_reason }}</p>
                <p><strong>金额:</strong> {{ selectedInfo.scammed_amount }}</p>
                <p><strong>Email:</strong> {{ selectedInfo.contact_email }}</p>
              </div>
              <div class="col">
                <div v-if="selectedInfo.image_paths">
                  <p><strong>证据截图:</strong></p>
                  <div v-for="path in selectedInfo.image_paths.split('#')" :key="path">
                    <img :src="path" alt="图片" @click="openImageModal(path)">
                  </div>
                </div>
              </div>
            </div>
            <label for="cloudBlackLevel">云黑等级:</label>
            <select id="cloudBlackLevel" v-model="selectedInfo.cloud_black_level">
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="approvePending(selectedInfo.id)">审核通过</button>
            <button type="button" class="btn btn-primary" @click="showRejectModal(selectedInfo)">审核不通过</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 审核不通过模态框 -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="rejectModalLabel">审核不通过</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="rejectReason">审核不通过原因:</label>
            <textarea id="rejectReason" v-model="rejectReason" rows="3" placeholder="请输入审核不通过的原因"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="rejectPending(selectedInfo.id)">确定</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 提示弹窗 -->
    <div class="toast-container">
      <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-header">
          <strong class="me-auto">提示</strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          {{ toastMessage }}
        </div>
      </div>
    </div>
  </div>

  <!-- 引入 Vue.js -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>

  <!-- 引入 jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- 引入 Bootstrap JavaScript 文件 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    new Vue({
      el: '#app',
      data: {
        pendingList: [],
        selectedInfo: {},
        rejectReason: '',
        toastMessage: ''
      },
      mounted() {
        this.getPendingList();
      },
      methods: {
        getPendingList() {
          fetch('get_pending_list.php')
            .then(response => response.json())
            .then(data => {
              this.pendingList = data;
            })
            .catch(error => {
              console.error('Error:', error);
            });
        },
        showDetails(item) {
          this.selectedInfo = { ...item };
          $('#detailsModal').modal('show');
        },
        approvePending(id) {
          if (this.selectedInfo.cloud_black_level === undefined) {
            alert('请选择云黑等级');
            return;
          }
          const formData = new FormData();
          formData.append('id', id);
          formData.append('cloud_black_level', this.selectedInfo.cloud_black_level);

          fetch('send_approval_email.php', {
            method: 'POST',
            body: formData
          })
            .then(response => response.json())
            .then(data => {
              console.log(data);
              if (data.success) {
                this.sendApprovalEmail(id); // 发送审核通过邮件
                this.showToast(data.message, 'success');
              } else {
                this.showToast(data.message, 'danger');
              }
              this.getPendingList(); // 刷新待审核列表
              $('#detailsModal').modal('hide');
              this.selectedInfo = {};
            })
            .catch(error => {
              console.error('Error:', error);
              this.showToast('出现错误，请重试', 'danger');
            });
        },
        sendApprovalEmail(id) {
          const formData = new FormData();
          formData.append('id', id);

          fetch('send_approval_email.php', {
            method: 'POST',
            body: formData
          })
            .then(response => response.json())
            .then(data => {
              console.log(data);
            })
            .catch(error => {
              console.error('Error:', error);
            });
        },
        showRejectModal(item) {
          this.selectedInfo = { ...item };
          $('#rejectModal').modal('show');
        },
        rejectPending(id) {
          if (this.rejectReason === '') {
            alert('请输入审核不通过的原因');
            return;
          }
          const formData = new FormData();
          formData.append('id', id);
          formData.append('reason', this.rejectReason);

          fetch('send_rejection_email.php', {
            method: 'POST',
            body: formData
          })
            .then(response => response.json())
            .then(data => {
              console.log(data);
              if (data.success) {
                this.showToast(data.message, 'success');
              } else {
                this.showToast(data.message, 'danger');
              }
              this.getPendingList(); // 刷新待审核列表
              $('#rejectModal').modal('hide');
              this.selectedInfo = {};
              this.rejectReason = '';
            })
            .catch(error => {
              console.error('Error:', error);
              this.showToast('出现错误，请重试', 'danger');
            });
        },
        showToast(message, type) {
          this.toastMessage = message;
          $('.toast').toast('show');
          $('.toast').addClass(`bg-${type}`);
        },
        openImageModal(path) {
          window.open(path, '_blank');
        }
      }
    });
  </script>
</body>
</html>
