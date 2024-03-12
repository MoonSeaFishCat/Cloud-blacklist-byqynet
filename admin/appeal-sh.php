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
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in pendingList" :key="item.id">
                <td>{{ item.id }}</td>
                <td>{{ item.black_info }}</td>
                <td>{{ item.black_reason }}</td>
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
                <p><strong>云黑信息:</strong> {{ selectedInfo.black_info }}</p>
                <p><strong>云黑原因:</strong> {{ selectedInfo.black_reason }}</p>
                <p><strong>申诉理由:</strong> {{ selectedInfo.appeal_reason }}</p>
                <p><strong>联系邮箱:</strong> {{ selectedInfo.contact_email }}</p>
                 <p><strong>提交时间:</strong> {{ selectedInfo.created_at }}</p>
              </div>
              <div class="col">
                <div v-if="selectedInfo.appeal_evidence">
                  <p><strong>证据截图:</strong></p>
                  <div v-for="path in selectedInfo.appeal_evidence.split('#')" :key="path">
                    <img :src="path" alt="图片">
                  </div>
                </div>
              </div>
            </div>
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
          fetch('../api/appeal/get_appeal.php')
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
          fetch('../api/appeal/approve_pending.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
          })
            .then(response => response.text())
            .then(data => {
              console.log(data);
              this.getPendingList();
              this.selectedInfo = {};
              $('#detailsModal').modal('hide');
              this.showToast('审核通过，数据写入成功', 'success');
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
          fetch('../api/appeal/send_email.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&decision=reject&content=${encodeURIComponent(this.rejectReason)}`
          })
            .then(response => response.json())
            .then(data => {
              console.log(data);
              this.getPendingList();
              this.selectedInfo = {};
              this.rejectReason = '';
              $('#rejectModal').modal('hide');
              this.showToast('审核不通过，邮件已发送', 'danger');
            })
            .catch(error => {
              console.error('Error:', error);
            });
        },
        showToast(message, type) {
          this.toastMessage = message;
          $('.toast').toast('show');
          $('.toast').addClass(`bg-${type}`);
        }
      }
    });
  </script>
</body>
</html>
