<?php
$client_id = '88daabc8d22745cc9ee35c206218daea';
$redirect_uri = 'http://localhost/MusicApp/callback.php'; // Đảm bảo rằng bạn đã đăng ký URI này trong cài đặt ứng dụng Spotify của bạn
$scope = 'streaming user-read-email user-read-private';

$params = array(
    'response_type' => 'code',
    'client_id' => $client_id,
    'scope' => $scope,
    'redirect_uri' => $redirect_uri,
);

$url = 'https://accounts.spotify.com/authorize?' . http_build_query($params);
header("Location: $url");
exit;
?>
