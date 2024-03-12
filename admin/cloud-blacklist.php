<?php
require('home.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>云黑名单</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
  <style>
    body {
      background-color: #f9e8ea;
      font-family: 'Comic Sans MS', cursive;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #FFF;
      border-radius: 10px;
      margin-top: 50px;
      box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #FF1493;
    }

    .search-container {
      text-align: center;
      margin-bottom: 20px;
    }

    .search-form input[type="text"] {
      padding: 10px;
      width: 300px;
      border: none;
      border-radius: 5px;
    }

    .search-form button {
      padding: 10px 20px;
      background-color: #FF69B4;
      border: none;
      border-radius: 5px;
      color: #FFF;
      cursor: pointer;
    }

    .filter-form {
      text-align: right;
      margin-bottom: 20px;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .table th {
      background-color: #FF69B4;
      color: #FFF;
    }

    .table td img {
      max-width: 100px;
      height: auto;
    }

    .table td .btn-delete {
      background-color: #FF0000;
      color: #FFF;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-details {
      background-color: #6495ED;
      color: #FFF;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0,0,0);
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      border-radius: 5px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div id="app" class="container">
    <h1>云黑名单</h1>

    <div class="search-container">
      <form class="search-form" @submit.prevent="searchBlacklist">
        <input type="text" v-model="searchTerm" placeholder="搜索云黑信息">
        <button type="submit">搜索</button>
      </form>
    </div>

    <div class="filter-form">
      <label>筛选等级:</label>
      <select v-model="selectedLevel" @change="filterBlacklist">
        <option value="">全部</option>
        <option v-for="level in levels" :value="level">{{ level }}</option>
      </select>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>云黑信息</th>
            <th>云黑原因</th>
            <th>涉案金额</th>
            <th>联系邮箱</th>
            <th>证据截图</th>
            <th>云黑等级</th>
            <th>创建时间</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in filteredBlacklist" :key="entry.id">
            <td>{{ entry.id }}</td>
            <td>{{ entry.cloud_black_info }}</td>
            <td>{{ entry.cloud_black_reason }}</td>
            <td>{{ entry.scammed_amount }}</td>
            <td>{{ entry.contact_email }}</td>
            <td>
              <template v-if="entry.image_paths">
                <img v-for="path in entry.image_paths.split('#')" :src="path" alt="证据截图" width="50">
              </template>
            </td>
            <td>{{ entry.cloud_black_level }}</td>
            <td>{{ entry.created_at }}</td>
            <td>
              <button class="btn btn-delete" @click="deleteBlacklistEntry(entry.id)">删除</button>
              <button class="btn-details" @click="showDetails(entry)">查看详情</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 模态框 -->
    <div class="modal" :style="{ display: showModal ? 'block' : 'none' }">
      <div class="modal-content">
        <span class="close" @click="closeDetails">&times;</span>
        <h2>详情</h2>
        <ul>
          <li>ID: {{ selectedEntry.id }}</li>
          <li>云黑信息: {{ selectedEntry.cloud_black_info }}</li>
          <li>云黑原因: {{ selectedEntry.cloud_black_reason }}</li>
          <li>涉案金额: {{ selectedEntry.scammed_amount }}</li>
          <li>联系邮箱: {{ selectedEntry.contact_email }}</li>
          <li>证据截图:</li>
          <li v-if="selectedEntry.image_paths">
            <img v-for="path in selectedEntry.image_paths.split('#')" :src="path" alt="证据截图" width="200">
          </li>
          <li>云黑等级: {{ selectedEntry.cloud_black_level }}</li>
          <li>创建时间: {{ selectedEntry.created_at }}</li>
        </ul>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
    <script>
      new Vue({
        el: '#app',
        data: {
          blacklist: [],
          searchTerm: '',
          selectedLevel: '',
          levels: [0, 1, 2, 3, 4],
          showModal: false,
          selectedEntry: {}
        },
        mounted() {
          this.fetchBlacklist();
        },
        computed: {
          filteredBlacklist() {
            return this.blacklist.filter(entry => {
              const searchTermMatch = entry.cloud_black_info.toLowerCase().includes(this.searchTerm.toLowerCase());
              const levelMatch = this.selectedLevel ? entry.cloud_black_level === parseInt(this.selectedLevel) : true;
              return searchTermMatch && levelMatch;
            });
          }
        },
        methods: {
          fetchBlacklist() {
            // Fetch blacklist data from the backend
            axios.get('fetch_blacklist.php')
              .then(response => {
                this.blacklist = response.data;
              })
              .catch(error => {
                console.log(error);
              });
          },
          searchBlacklist() {
            // Fetch blacklist data again to update the filtered results
            this.fetchBlacklist();
          },
          filterBlacklist() {
            // Filter the blacklist based on selected level
            this.fetchBlacklist();
          },
          deleteBlacklistEntry(id) {
            Swal.fire({
              title: '确认删除',
              text: '您确定要删除此条云黑信息吗？',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: '删除',
              cancelButtonText: '取消'
            }).then((result) => {
              if (result.isConfirmed) {
                // Delete the entry with the specified ID from the blacklist
                axios.delete(`fetch_blacklist.php?id=${id}`)
                  .then(response => {
                    // Remove the entry from the local blacklist
                    this.blacklist = this.blacklist.filter(entry => entry.id !== id);
                    Swal.fire(
                      '删除成功',
                      '该条云黑信息已成功删除。',
                      'success'
                    );
                  })
                  .catch(error => {
                    console.log(error);
                    Swal.fire(
                      '删除失败',
                      '删除过程中发生错误。',
                      'error'
                    );
                  });
              }
            });
          },
          showDetails(entry) {
            this.selectedEntry = entry;
            this.showModal = true;
          },
          closeDetails() {
            this.selectedEntry = {};
            this.showModal = false;
          }
        }
      });
    </script>
  </div>
</body>
</html>
