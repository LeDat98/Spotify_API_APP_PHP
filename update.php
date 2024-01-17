<?php
// Tạo kết nối
$pdo = new PDO('mysql:host=localhost;dbname=musicapp;charset=utf8', 'root', '');

// Lấy dữ liệu JSON gửi đến từ client
$json = file_get_contents('php://input');
$data = json_decode($json);

// Dữ liệu bài hát
$user_id = $data ->user_id;
$songName = $data->songName;
$artistName = $data->artistName;
$imageUrl = $data->imageUrl;
$songUri = $data->songUri;


// Chuẩn bị câu lệnh SQL và chèn dữ liệu vào cơ sở dữ liệu
$stmt = $pdo->prepare("INSERT INTO musicapp (user_id, songName, artistName, imageUrl, uri) VALUES (:user_id, :songName, :artistName, :imageUrl, :uri)");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':songName', $songName);
$stmt->bindParam(':artistName', $artistName);
$stmt->bindParam(':imageUrl', $imageUrl);
$stmt->bindParam(':uri', $songUri);

// Thực thi câu lệnh
if($stmt->execute()) {
    echo json_encode(array("message" => "Bài hát đã được thêm vào danh sách phát"));
} else {
    echo json_encode(array("message" => "Lỗi khi thêm bài hát vào danh sách phát"));
}
?>
