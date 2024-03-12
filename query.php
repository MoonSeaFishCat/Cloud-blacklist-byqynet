<!DOCTYPE html>
<html lang="zh-cn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>轻之忆云黑---系统查询</title>
  <!-- 引入样式文件 -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/style2.css">
  <link rel="stylesheet" href="assets/css/Color.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="assets/css/modal.css">
  <link rel="stylesheet" href="assets/css/custom.css"> <!-- 新的样式文件 -->
  <link rel="stylesheet" href="assets/css/lightbox.css">
  <style>
    /* 修改样式以实现白桃粉主色调 */
    body {
      background-color: #FFF4F7;
    }

    .auth-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .auth-container {
      background-color: #FFF;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
      width: 100%;
      border: 1px solid #FFC1E0;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 16px rgba(255, 90, 144, 0.3);
      animation: borderAnimation 5s infinite linear;
    }

    @keyframes borderAnimation {
      0% {
        border-color: #FFC1E0;
      }

      50% {
        border-color: #FF5A90;
      }

      100% {
        border-color: #FFC1E0;
      }
    }

    .auth-submit {
      background-color: #F9C4D2;
      border: 1px solid #F9C4D2;
      color: white;
      padding: 10px 20px;
      border-radius: 20px;
      cursor: pointer;
    }

    .auth-submit:hover {
      background-color: #FF5A90;
      border-color: #FF5A90;
    }

    .auth-sgt,
    .touwei {
      color: #FF5A90;
      text-align: center;
    }

    .auth-form-outer,
    .auth-forgot-password1 {
      border: 1px solid #FF5A90;
    }

    .BiaoTi {
      color: #FF5A90;
    }

    .result-modal {
      max-height: 300px;
      overflow-y: auto;
    }

    .result-modal p {
      margin-bottom: 10px;
    }

    .result-modal img {
      max-width: 100%;
      object-fit: contain;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="auth-wrapper">
    <div class="auth-container">
      <div class="auth-action-left">
        <div class="auth-form-outer">
          <h2 class="auth-form-title">轻之忆云黑---系统查询</h2>
          <div class="auth-external-container">
            <p class="auth-sgt"><font size="4">请输入账号或群号查询:</font></p>
          </div>
          <form class="form-sign" method="post" id="queryForm">
            <input type="text" class="auth-form-input" name="qq" id="qq" placeholder="在此处输入...">
            <div class="footer-action">
              <input type="submit" value="查询" class="auth-submit">
            </div>
          </form>
          <div class="auth-forgot-password1">
            <a id="CheckText">
              <center>
                <label class="touwei">请在上方输入云黑信息查询</label><br><label class="touwei">查询仅供参考 具体以实际为准</label>
              </center>
            </a>
          </div>
        </div>
      </div>
    </div>

    <center>
      <br>
      <a rel="nofollow">&copy; 2023~2025轻忆工作室版权所有.</a>
      <br>
      <br>
    </center>
  </div>

  <!-- 引入SweetAlert2的JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
  <script src="js/common.js"></script>
  <script>
    // 监听查询表单的提交事件
    document.getElementById('queryForm').addEventListener('submit', function(e) {
      e.preventDefault(); // 阻止表单默认提交行为

      // 获取查询的值
      var queryValue = document.getElementById('qq').value;

      // 发起异步请求，这里使用了fetch函数
      fetch('./api/query.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'data=' + encodeURIComponent(queryValue),
      })
      .then(function(response) {
        if (response.ok) {
          return response.json();
        } else {
          throw new Error('未查询到该信息，请注意交易安全');
        }
      })
      .then(function(data) {
        // 处理查询结果
        if (data.success) {
          // 查询成功，显示结果
          var result = data.data;
          var resultText = '';
          if (result && result.length > 0) {
            resultText += '<div class="result-container">';
            for (var i = 0; i < result.length; i++) {
              resultText += '<div class="result-item">';
              resultText += '<p><strong>ID:</strong> ' + result[i].id + '</p>';
              resultText += '<p><strong>云黑信息:</strong> ' + result[i].cloud_black_info + '</p>';
              resultText += '<p><strong>云黑原因:</strong> ' + result[i].cloud_black_reason + '</p>';
              resultText += '<p><strong>被骗金额:</strong> ' + result[i].scammed_amount + '</p>';
              resultText += '<p><strong>云黑等级:</strong> ' + result[i].cloud_black_level + '</p>';
              var imagePaths = result[i].image_paths;
              if (imagePaths) {
                var imagePathsArr = imagePaths.split(';');
                for (var j = 0; j < imagePathsArr.length; j++) {
                  var imagePath = imagePathsArr[j];
                  resultText += '<img src="' + imagePath + '" onclick="openImageModal(\'' + imagePath + '\')">';
                }
              }
              resultText += '</div>';
            }
            resultText += '</div>';
          } else {
            resultText = '未查询到该信息，请注意交易安全';
          }

          // 使用SweetAlert2显示结果
          Swal.fire({
            icon: 'success',
            title: '查询成功',
            html: resultText,
            width: '80%',
            confirmButtonColor: '#F9C4D2',
            scrollbarPadding: false,
            customClass: {
              scrollbar: 'swal2-scrollbar'
            },
            onAfterRender: function() {
              Swal.getHtmlContainer().classList.add('swal2-show-scrollbar');
            }
          });
        } else {
          // 查询失败，显示错误信息
          Swal.fire({
            icon: 'error',
            title: '查询失败',
            text: data.message,
          });
        }
      })
      .catch(function(error) {
        // 捕获异常错误
        Swal.fire({
          icon: 'error',
          title: '查询失败',
          text: error.message,
        });
      });
    });

    // 函数用于打开模态框显示图片
    function openImageModal(imagePath) {
      const modal = document.createElement('div');
      modal.classList.add('image-modal');
      modal.innerHTML = `
        <div class="image-modal-content">
          <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
          <img src="${imagePath}" class="image-modal-img">
        </div>
      `;
      document.body.appendChild(modal);
    }

    // 函数用于关闭图片模态框
    function closeImageModal() {
      const modal = document.querySelector('.image-modal');
      if (modal) {
        modal.remove();
      }
    }
  </script>

  <!-- 引入模态框的CSS和JavaScript文件 -->
  <link rel="stylesheet" href="assets/css/lightbox.css">
  <script src="assets/js/lightbox.js"></script>
</body>

</html>
