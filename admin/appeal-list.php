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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
  <style>
    body {
      background-color: #FFE6EC;
    }
    .table-container {
      max-width: 800px;
      margin: 0 auto;
    }
    .table-container table {
      background-color: #FFF5F9;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
    .table-container th {
      background-color: #FFB1C1;
      color: #FFFFFF;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .table-container td {
      font-size: 14px;
      vertical-align: middle;
    }
    .btn-primary {
      background-color: #FFB1C1;
      border-color: #FFB1C1;
    }
    .btn-primary:hover {
      background-color: #FF7B8D;
      border-color: #FF7B8D;
    }
    .btn-danger {
      background-color: #FF5A76;
      border-color: #FF5A76;
    }
    .btn-danger:hover {
      background-color: #FF274C;
      border-color: #FF274C;
    }
    .modal-content {
      background-color: #FFF5F9;
    }
    .modal-header {
      background-color: #FFB1C1;
      color: #FFFFFF;
      border-radius: 10px 10px 0 0;
    }
    .modal-title {
      font-size: 24px;
      font-weight: bold;
    }
    .modal-body {
      padding: 20px;
    }
    .img-fluid {
      max-width: 100%;
      height: auto;
    }
  </style>
  <title>申诉信息表</title>
</head>
<body>
  <div id="app" class="container mt-5">
    <h2 class="text-center" style="color: #FF5A76;">申诉信息表</h2>
    <div class="table-container">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="background-color: #FF5A76;">黑名单信息</th>
            <th style="background-color: #FF5A76;">黑名单原因</th>
            <th style="background-color: #FF5A76;">申诉原因</th>
            <th style="background-color: #FF5A76;">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="appeal in appeals" :key="appeal.id">
            <td>{{ appeal.black_info }}</td>
            <td>{{ appeal.black_reason }}</td>
            <td>{{ appeal.appeal_reason }}</td>
            <td>
              <button class="btn btn-primary btn-sm" @click="showDetails(appeal)">查看详情</button>
              <button class="btn btn-danger btn-sm" @click="deleteAppeal(appeal.id)">删除</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios@0.24.0/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        appeals: [], // 申诉信息数组
      },
      mounted() {
        this.fetchAppeals(); // 获取申诉信息
      },
      methods: {
        fetchAppeals() {
          // 从后端获取申诉信息数据
          axios.get('../api/appeal/fetch_appeals.php')
            .then(response => {
              this.appeals = response.data;
            })
            .catch(error => {
              console.error(error);
            });
        },
        showDetails(appeal) {
          // 显示申诉信息详情
          const content = `
            <h5 class="modal-title">${appeal.black_info}</h5>
            <p><strong>黑名单原因:</strong> ${appeal.black_reason}</p>
            <p><strong>申诉原因:</strong> ${appeal.appeal_reason}</p>
            <p><strong>联系邮箱:</strong> ${appeal.contact_email}</p>
            <p><strong>创建时间:</strong> ${appeal.created_at}</p>
            <p><strong>申诉证据:</strong></p>
            ${this.renderEvidence(appeal.appeal_evidence)}
          `;
          Swal.fire({
            title: '申诉信息详情',
            html: content,
            showCloseButton: true,
            showConfirmButton: false,
            focusConfirm: false,
            customClass: {
              container: 'swal2-white-peach',
              header: 'swal2-white-peach',
              content: 'swal2-white-peach',
            },
          });
        },
        deleteAppeal(id) {
          // 发送请求删除申诉信息
          Swal.fire({
            title: '确认删除？',
            text: '删除后无法恢复该申诉信息！',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            customClass: {
              container: 'swal2-white-peach',
              header: 'swal2-white-peach',
              content: 'swal2-white-peach',
            },
          }).then((result) => {
            if (result.isConfirmed) {
              axios.delete(`../api/appeal/delete_appeal.php?id=${id}`)
                .then(response => {
                  Swal.fire({
                    title: '删除成功',
                    icon: 'success',
                    customClass: {
                      container: 'swal2-white-peach',
                      header: 'swal2-white-peach',
                      content: 'swal2-white-peach',
                    },
                  });
                  this.fetchAppeals();
                })
                .catch(error => {
                  console.error(error);
                  Swal.fire({
                    title: '删除失败',
                    icon: 'error',
                    customClass: {
                      container: 'swal2-white-peach',
                      header: 'swal2-white-peach',
                      content: 'swal2-white-peach',
                    },
                  });
                });
            }
          });
        },
        renderEvidence(evidence) {
          // 解析申诉证据图片路径，并返回HTML代码
          const images = evidence.split('#');
          let html = '';
          for (const image of images) {
            html += `<img src="${image}" alt="申诉证据" class="img-fluid mb-3">`;
          }
          return html;
        }
      }
    });
  </script>
</body>
</html>
