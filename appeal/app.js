
// 定义一个函数来处理表单提交
function submitForm() {
  const cloudBlackLevel = 0; // 在此处设置 cloudBlackLevel 的值
  const cloudBlackReason = document.getElementById('cloud-black-reason').value;
  const appealReason = document.getElementById('appeal-reason').value;
  const contactEmail = document.getElementById('contact-email').value;
  const appealEvidence = document.getElementById('appeal-evidence').files;

  // 创建一个新的 FormData 对象并添加表单数据
  const formData = new FormData();
  formData.append('cloud_black_level', cloudBlackLevel);
  formData.append('cloud_black_reason', cloudBlackReason);
  formData.append('appealReason', appealReason);
  formData.append('contactEmail', contactEmail);

  // 将文件添加到 FormData 对象中
  for (let i = 0; i < appealEvidence.length; i++) {
    formData.append('appealEvidence[]', appealEvidence[i]);
  }

  // 使用 fetch API 发送 POST 请求到 PHP 脚本
  fetch('i-v.php', {
    method: 'POST',
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      // 检查响应状态
      if (data.status === 'success') {
        // 如果响应状态为 success，则继续提交表单到服务器
        submitFormToServer(formData);
      } else {
        // 如果响应状态为 error，则显示警告
        alert(data.message);
      }
    })
    .catch(error => {
      console.error('错误:', error);
    });
}

// 定义一个函数将表单数据提交到服务器（submit.php）
function submitFormToServer(formData) {
  // 使用 fetch API 发送另一个 POST 请求到服务器
  fetch('submit.php', {
    method: 'POST',
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      // 处理服务器返回的响应
      if (data.success) {
        // 如果提交成功，则显示成功消息
        alert(data.message);
        // 可选地，在此处清空表单字段
        document.getElementById('cloud-black-reason').value = '';
        document.getElementById('appeal-reason').value = '';
        document.getElementById('contact-email').value = '';
        document.getElementById('appeal-evidence').value = '';
      } else {
        // 如果提交失败，则显示错误消息
        alert(data.message);
      }
    })
    .catch(error => {
      console.error('错误:', error);
    });
}

// 将 submitForm 函数附加到表单的提交事件上
document.getElementById('my-form').addEventListener('submit', event => {
  event.preventDefault();
  submitForm();
});
