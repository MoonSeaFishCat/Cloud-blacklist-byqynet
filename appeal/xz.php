<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>申诉须知</title>
    <!-- 引入Bootstrap样式 -->
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css">
    <!-- 引入Vue -->
    <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.14/vue.min.js"></script>
    <style>
        body {
            background-color: #f9f9f9;
        }

        .content-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
        }

        .banner-card {
            background-color: #FFFFFF;
            color: #fff; /* White */
            padding: 10px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #e91e63; /* Vivid Pink */
            border-color: #e91e63; /* Vivid Pink */
            display: block;
            margin: 0 auto; /* Center the button horizontally */
        }

        .btn-primary:hover {
            background-color: #c2185b; /* Dark Pink */
            border-color: #c2185b; /* Dark Pink */
        }

        h1, h2, h3, h4, h5, h6 {
            color: #e91e63; /* Vivid Pink */
            text-align: center;
        }

        ul, ol {
            margin-left: 20px;
            text-align: left;
        }

        .anime-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div id="app" class="container my-5">
    <div class="content-card">
        <h1 class="mb-4">申诉须知</h1>
        <div class="banner-card">
            <h3 class="mb-0">只接受云黑等级3级及以下的申诉请求，且需要提供合适的证据材料</h3>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <p class="lead mb-4">1.每次申诉需要填写申诉申请表，且需要提交申诉证据及联系方式（必须填写qq）。</p>
                <p class="mb-4">2.云黑账号的申诉受理有效期为1个月，超过一个月没有解黑的视为跑路，不予受理。</p>
                <p class="mb-4">3.以下情况同样不予受理：</p>
                <ul class="mb-4">
                    <li>代解黑/亲友帮解黑无法受理。原因：本人都不敢露面我们也无能为力。</li>
                    <li>超过半年后来解黑的账号且举报人联系不上。原因：举报人已退圈且您对举报人的赔偿时效已到。</li>
                </ul>
                <p class="mb-4">流程：</p>
                <ol class="mb-4">
                    <li>准备好申诉的证据或举报人同意的同意书。</li>
                    <li>填写申请下黑的表单，如有同意书，无需提交证据截图，直接填写表单并提交。</li>
                    <li>等待处理（一般1周内）。</li>
                </ol>
                <p class="mb-4">请监督我们：</p>
                <ol class="mb-4">
                    <li>管理员提出不合理要求。</li>
                    <li>对我们不满意且有正当理由。</li>
                    <li>任意管理员出现收费解黑行为。</li>
                </ol>
                <p class="mb-4">出现以上问题，请邮箱联系isqynetkj@outlook.com。轻之忆仅负责系统技术支持，不再参与云黑事务。</p>
                <!-- 添加同意并申请下黑按钮，点击按钮跳转到xiahei.php -->
                <button class="btn btn-primary mt-4" @click="apply()">同意并申请下黑</button>
                <div class="card mt-4">
                    <div class="card-body">
                        <h2 class="card-title">同意书</h2>
                        <p class="card-text">举报人用自己的qq和被举报人的聊天记录中发出"同意下黑"即可（不可打码）</p>
                        <p class="card-text">只需上传这张截图（必须包含关键词）</p>
                     </div>
        </div>
    </div>
</div>
<!-- 引入Bootstrap脚本 -->
<script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.min.js"></script>
<script>
    // Vue实例
    var app = new Vue({
        el: '#app',
        methods: {
            // 点击按钮后跳转到xiahei.php
            apply: function() {
                window.location.href = "index.php";
            }
        }
    });
</script>
</body>
</html>

