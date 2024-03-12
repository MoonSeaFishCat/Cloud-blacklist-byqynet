<?php
// 函数用于检测用户代理是否来自移动设备
function isMobileDevice()
{
    return preg_match('/(Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini)/i', $_SERVER['HTTP_USER_AGENT']);
}

// 判断用户代理是否来自移动设备或 PC
if (isMobileDevice()) {
    // 加载移动设备版本的 index.php
    include 'template/mobile/index.php';
} else {
    // 加载 PC 版本的 index.php
    include 'template/PC/index.php';
}
?>
