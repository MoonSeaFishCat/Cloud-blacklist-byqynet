<!DOCTYPE html>
<html>
<head>
  <title>云黑申诉</title>
  <!-- 引入 Materialize CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <style>
    body {
      background-color: #f9e8ea;
    }
    
    .container {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-top: 50px;
    }
    
    .form-label {
      color: #ff6596;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    /* 给文本标签添加动画效果 */
    .form-label.active {
      transform: translateY(-20px);
      font-size: 12px;
    }
    
    .btn-apply {
      background-color: #ff6596;
      border-color: #ff6596;
    }
    
    .btn-apply:hover {
      background-color: #ff4f82;
      border-color: #ff4f82;
    }
    
    .preview-image {
      width: 100%;
      max-width: 200px;
      margin-top: 10px;
    }

    /* 调整较小设备的边距 */
    @media only screen and (max-width: 600px) {
      .preview-image {
        margin: 5px;
      }
    }
  </style>
  <!-- 移动设备响应式设置 -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div id="app">
    <div class="container">
      <div class="row">
        <div class="col s12">
          <h2 class="center-align mt-5">云黑申诉</h2>
          <form @submit.prevent="submitForm" class="mt-4">
            <div class="input-field">
              <label for="cloud-black-info" class="form-label" :class="{ 'active': cloudBlackInfo }">被云黑信息</label>
              <input type="text" id="cloud-black-info" v-model="cloudBlackInfo" required>
            </div>
            <div class="input-field">
              <label for="cloud-black-reason" class="form-label" :class="{ 'active': cloudBlackReason }">云黑原因</label>
              <input type="text" id="cloud-black-reason" v-model="cloudBlackReason" required>
            </div>
            <div class="input-field">
              <label for="appeal-reason" class="form-label" :class="{ 'active': appealReason }">申诉理由</label>
              <textarea class="materialize-textarea" id="appeal-reason" v-model="appealReason" required></textarea>
            </div>
            <div class="input-field">
              <div class="file-field">
                <div class="btn btn-apply">
                  <span>上传证据</span>
                  <!-- 添加一个标签来触发文件输入 -->
                  <input type="file" id="file-input" class="file-input" ref="fileInput" @change="handleFileUpload" accept="image/jpeg, image/png" multiple>
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate" type="text" placeholder="选择证据图片 (jpg, png)">
                </div>
              </div>
            </div>
            <div class="d-flex flex-wrap">
              <!-- 使用 v-for 循环遍历选定的文件并显示预览 -->
              <img class="preview-image mr-2 mb-2" v-for="(url, index) in previewUrls" :src="url" :key="index" alt="预览">
            </div>
            <div class="input-field">
              <label for="contact-email" class="form-label" :class="{ 'active': contactEmail }">联系方式（邮箱）</label>
              <input type="email" id="contact-email" v-model="contactEmail" required>
            </div>
            <div class="center-align">
              <button type="submit" class="btn btn-apply">提交</button>
              <button type="button" class="btn btn-secondary ml-2" @click="clearForm">重置</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios@0.23.0"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        cloudBlackInfo: '',
        cloudBlackReason: '',
        appealReason: '',
        contactEmail: '',
        files: [],
        previewUrls: [],
        cloudBlackLevel: 0,
      },
      methods: {
        async handleFileUpload(event) {
          this.files = Array.from(event.target.files);
          this.previewUrls = [];

          for (const file of this.files) {
            this.previewUrls.push(await this.readFileAsDataURL(file));
          }
        },
        readFileAsDataURL(file) {
          return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
            reader.readAsDataURL(file);
          });
        },
        async submitForm() {
          try {
            const validationResponse = await axios.post('/api/i-v.php', this.getFormData(), {
              headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (validationResponse.data !== 200) {
              Swal.fire('警告', '你所提交的信息不接受申诉！', 'warning');
              return;
            }

            const appealResponse = await axios.post('/api/submit-appeal.php', this.getFormData(), {
              headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (appealResponse.data.success) {
              Swal.fire('成功', appealResponse.data.message, 'success');
              this.clearForm();
            } else {
              Swal.fire('失败', appealResponse.data.message, 'error');
            }
          } catch (error) {
            this.handleError(error);
          }
        },
        getFormData() {
          const formData = new FormData();
          formData.append('cloud_black_level', this.cloudBlackLevel);
          formData.append('cloud_black_reason', this.cloudBlackReason);
          formData.append('contact_email', this.contactEmail);

          this.files.forEach((file) => {
            formData.append('cloud_black_evidence[]', file);
          });

          return formData;
        },
        handleError(error) {
          if (error.response) {
            Swal.fire('错误', error.response.data.message, 'error');
          } else {
            Swal.fire('错误', '申诉验证请求失败', 'error');
          }
        },
        clearForm() {
          this.cloudBlackInfo = '';
          this.cloudBlackReason = '';
          this.appealReason = '';
          this.contactEmail = '';
          this.files = [];
          this.previewUrls = [];
          this.$refs.fileInput.value = '';
        }
      }
    });
  </script>
</body>
</html>