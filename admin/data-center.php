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
  <title>Data Center</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f9e8ea;
    }
    .container {
      margin-top: 50px;
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
    }
    h1 {
      color: #ff4785;
      text-align: center;
      margin-bottom: 30px;
    }
    .card {
      margin-bottom: 20px;
    }
    .card-title {
      color: #ff4785;
      font-size: 18px;
      font-weight: bold;
    }
    .card-text {
      font-size: 16px;
      margin-bottom: 0;
    }
    #chart {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div id="app" class="container">
    <h1>数据中心</h1>
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4" v-for="card in cards" :key="card.title">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ card.title }}</h5>
            <p class="card-text">数量：{{ card.count }}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4 justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title">管理员列表</h5>
            <p class="card-text">数量：{{ adminCount }}</p>
          </div>
        </div>
      </div>
    </div>
    <div id="chart" class="mt-4">
      <canvas id="myChart"></canvas>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        cards: [],
        adminCount: 0
      },
      mounted() {
        this.loadData();
      },
      methods: {
        loadData() {
          const apiUrl ='data-center-api.php'; // Replace with the actual API URL
          axios.get(apiUrl)
            .then(response => {
              const data = response.data;
              this.cards = [
                { title: '云黑信息数', count: data.cloudBlacklistCount },
                { title: '申诉信息数', count: data.appealCount }
              ];
              this.adminCount = data.adminCount;
              this.$nextTick(() => {
                this.renderChart();
              });
            })
            .catch(error => {
              console.error(error);
              // Handle error
            });
        },
        renderChart() {
          const ctx = document.getElementById('myChart').getContext('2d');
          const chart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: this.cards.map(card => card.title),
              datasets: [{
                label: '数量',
                data: this.cards.map(card => card.count),
                backgroundColor: '#ff4785',
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        }
      }
    });
  </script>
</body>
</html>
