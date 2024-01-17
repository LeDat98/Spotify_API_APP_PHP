<?php
// Kết nối cơ sở dữ liệu
$pdo = new PDO('mysql:host=localhost;dbname=musicapp;charset=utf8', 'root', '');

// Kiểm tra xem ID có tồn tại không
if (isset($_GET['uri'])) {
    $songUri = $_GET['uri']; // Hoặc thay thế bằng $songName nếu bạn dùng tên bài hát

    // Câu lệnh SQL để xóa bài hát
    $sql = "DELETE FROM musicapp WHERE uri = :uri"; // Hoặc thay thế 'uri' bằng 'songName'

    // Chuẩn bị và thực thi câu lệnh
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uri', $songUri); // Hoặc thay thế ':uri' bằng ':songName' và $songUri bằng tên bài hát
    $stmt->execute();

    // Chuyển hướng về trang chính hoặc trang khác sau khi xóa
    header("Location: index.php"); // Hoặc trang bạn muốn chuyển hướng đến
} else {
    echo "Không tìm thấy ID bài hát để xóa.";
}
?>
