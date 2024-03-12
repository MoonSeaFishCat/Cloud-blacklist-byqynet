<!DOCTYPE html>
<html>
<head>
    <title>云黑申诉</title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/sweetalert2.min.css">
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
            width: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center mt-5">云黑申诉</h2>
                <form @submit.prevent="submitForm" class="mt-4">
                    <div class="mb-3">
                        <label for="cloud-black-info" class="form-label">被云黑信息</label>
                        <input type="text" class="form-control" id="cloud-black-info" v-model="cloudBlackInfo" required>
                    </div>
                    <div class="mb-3">
                        <label for="cloud-black-reason" class="form-label">云黑原因</label>
                        <input type="text" class="form-control" id="cloud-black-reason" v-model="cloudBlackReason" required>
                    </div>
                    <div class="mb-3">
                        <label for="appeal-reason" class="form-label">申诉理由</label>
                        <textarea class="form-control" id="appeal-reason" v-model="appealReason" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="appeal-evidence" class="form-label">申诉证据</label>
                        <input type="file" class="form-control" id="appeal-evidence" ref="fileInput" multiple @change="handleFileUpload" accept="image/jpeg, image/png">
                        <div class="d-flex flex-wrap">
                            <img class="preview-image mr-2 mb-2" v-for="url in previewUrls" :src="url" :key="url" alt="Preview">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="contact-email" class="form-label">联系方式（邮箱）</label>
                        <input type="email" class="form-control" id="contact-email" v-model="contactEmail" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">提交</button>
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
  <script src="app.js"></script>
  <script>
    new Vue({
        el: '#app',
        data: {
            cloudBlackInfo: '',
            cloudBlackReason: '',
            appealReason: '',
            contactEmail: '',
            files: [],
            previewUrls: []
        },
        methods: {
            handleFileUpload(event) {
                this.files = Array.from(event.target.files);
                this.previewUrls = [];

                this.files.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = () => {
                        this.previewUrls.push(reader.result);
                    };
                    reader.readAsDataURL(file);
                });
            },
            submitForm() {
                const formData = new FormData();
                formData.append('cloudBlackInfo', this.cloudBlackInfo);
                formData.append('cloudBlackReason', this.cloudBlackReason);
                formData.append('appealReason', this.appealReason);
                formData.append('contactEmail', this.contactEmail);

                this.files.forEach((file) => {
                    formData.append('cloudBlackEvidence[]', file);
                });

                axios.post('submit.php', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then((response) => {
                        if (response.data.success) {
                            Swal.fire('成功', response.data.message, 'success');
                            this.clearForm();
                        } else {
                            Swal.fire('失败', response.data.message, 'error');
                        }
                    })
                    .catch((error) => {
                        Swal.fire('错误', '申诉提交失败', 'error');
                    });
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
