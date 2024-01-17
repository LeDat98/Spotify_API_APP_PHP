<?php
// Bắt đầu hoặc mở phiên hiện tại
session_start();

// Hủy bỏ tất cả các biến session
session_unset();

// Hủy bỏ phiên làm việc
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập (hoặc trang chính của bạn)
header("Location: login.html");
exit();
?>
