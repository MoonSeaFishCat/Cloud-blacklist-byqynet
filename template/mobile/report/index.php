<!DOCTYPE html>
<html>
<head>
  <title>申请上黑</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

  <style>
    body {
      background-color: #f9e8ea;
    }
    
    .card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-top: 20px;
    }
    
    .form-label {
      color: #ff6596;
    }
    
    .btn-primary {
      background-color: #ff6596;
      border-color: #ff6596;
    }
    
    .btn-primary:hover {
      background-color: #ff4f82;
      border-color: #ff4f82;
    }
    
    .preview-image {
      width: 100%;
      max-width: 200px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div id="app" class="container my-5">
    <div class="card">
      <h2 class="text-center mt-2">申请上黑</h2>
      <form @submit.prevent="submitForm" class="mt-4">
        <div class="mb-3">
          <label for="cloud-black-info" class="form-label">云黑信息</label>
          <input type="text" class="form-control" id="cloud-black-info" v-model="cloudBlackInfo" required>
        </div>
        <div class="mb-3">
          <label for="cloud-black-reason" class="form-label">云黑原因</label>
          <textarea class="form-control" id="cloud-black-reason" v-model="cloudBlackReason" required></textarea>
        </div>
        <div class="mb-3">
          <label for="scammed-amount" class="form-label">被骗金额</label>
          <input type="number" class="form-control" id="scammed-amount" v-model="scammedAmount" required>
        </div>
        <div class="mb-3">
          <label for="cloud-black-evidence" class="form-label">云黑证据</label>
          <input type="file" class="form-control" id="cloud-black-evidence" ref="evidenceInput" @change="previewImages" multiple required>
          <div class="d-flex flex-wrap">
            <img class="preview-image mr-2 mb-2" v-for="url in previewUrls" :src="url" :key="url" alt="Preview">
          </div>
        </div>
        <div class="mb-3">
          <label for="contact-email" class="form-label">联系方式（邮箱）</label>
          <input type="email" class="form-control" id="contact-email" v-model="contactEmail" required>
        </div>
        
        <div class="text-center">
          <button type="submit" class="btn btn-primary">提交申请</button>
          <button type="button" class="btn btn-secondary ml-2" @click="resetForm">重置表单</button>
          <a class="btn btn-primary" href="https://wj.qq.com/s2/12770945/27c8/">申请惩戒</a>
        </div>
      </form>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        cloudBlackInfo: '',
        cloudBlackReason: '',
        scammedAmount: '',
        cloudBlackEvidence: [],
        previewUrls: [],
        contactEmail: ''
      },
      methods: {
        submitForm() {
          if (this.containsSQL(this.cloudBlackInfo) || this.containsSQL(this.cloudBlackReason) || this.containsSQL(this.contactEmail)) {
            Swal.fire({
              icon: 'error',
              title: '非法数据',
              text: '输入的数据包含不合法的内容，请重新检查。',
              confirmButtonText: '确认',
              confirmButtonColor: '#ff6596',
              customClass: {
                popup: 'swal-popup',
                title: 'swal-title',
                content: 'swal-content',
                confirmButton: 'swal-confirm-button'
              }
            });
          } else {
            // Create FormData object
            const formData = new FormData();
            formData.append('cloudBlackInfo', this.cloudBlackInfo);
            formData.append('cloudBlackReason', this.cloudBlackReason);
            formData.append('scammedAmount', this.scammedAmount);
            formData.append('contactEmail', this.contactEmail);
            for (let i = 0; i < this.cloudBlackEvidence.length; i++) {
              formData.append('cloudBlackEvidence[]', this.cloudBlackEvidence[i]);
            }

            // Send AJAX request
            axios.post('/api/submit_jubao.php', formData)
              .then(response => {
                if (response.data.success) {
                  Swal.fire({
                    icon: 'success',
                    title: '申请成功',
                    text: '感谢您的申请，我们会尽快处理。',
                    confirmButtonText: '确认',
                    confirmButtonColor: '#ff6596',
                    customClass: {
                      popup: 'swal-popup',
                      title: 'swal-title',
                      content: 'swal-content',
                      confirmButton: 'swal-confirm-button'
                    }
                  }).then(() => {
                    this.resetForm();
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: '申请失败',
                    text: '申请提交失败，请稍后再试。',
                    confirmButtonText: '确认',
                    confirmButtonColor: '#ff6596',
                    customClass: {
                      popup: 'swal-popup',
                      title: 'swal-title',
                      content: 'swal-content',
                      confirmButton: 'swal-confirm-button'
                    }
                  });
                }
              })
              .catch(error => {
                Swal.fire({
                  icon: 'error',
                  title: '申请失败',
                  text: '申请提交失败，请稍后再试。',
                  confirmButtonText: '确认',
                  confirmButtonColor: '#ff6596',
                  customClass: {
                    popup: 'swal-popup',
                    title: 'swal-title',
                    content: 'swal-content',
                    confirmButton: 'swal-confirm-button'
                  }
                });
                console.error(error);
              });
          }
        },
        previewImages(event) {
          this.cloudBlackEvidence = [];
          this.previewUrls = [];
          const files = event.target.files;
          for (let i = 0; i < files.length; i++) {
            const file = files[i];
            this.cloudBlackEvidence.push(file);
            const reader = new FileReader();
            reader.onload = e => {
              this.previewUrls.push(e.target.result);
            };
            reader.readAsDataURL(file);
          }
        },
        resetForm() {
          this.cloudBlackInfo = '';
          this.cloudBlackReason = '';
          this.scammedAmount = '';
          this.cloudBlackEvidence = [];
          this.previewUrls = [];
          this.contactEmail = '';
          this.$refs.evidenceInput.value = '';
        },
        containsSQL(input) {
          const sqlKeywords = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER'];
          const regex = new RegExp(`\\b(${sqlKeywords.join('|')})\\b`, 'i');
          return regex.test(input);
        }
      }
    });
  </script>
</body>
</html>
