<?php
if (isset($_GET['code'])) {
    $auth_code = $_GET['code'];
    $client_id = '88daabc8d22745cc9ee35c206218daea';
    $client_secret = 'a7c712e71ef9487bbb05ea0db3fb4b1c';
    $redirect_uri = 'http://localhost/MusicApp/callback.php';

    $url = 'https://accounts.spotify.com/api/token';

    $data = array(
        'grant_type' => 'authorization_code',
        'code' => $auth_code,
        'redirect_uri' => $redirect_uri
    );

    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                         "Authorization: Basic " . base64_encode("$client_id:$client_secret"),
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseData = json_decode($response, true);
    
    // Bạn có thể lưu token vào session hoặc cookie tùy thuộc vào cách bạn muốn quản lý token
    session_start();
    $_SESSION['access_token'] = $responseData['access_token'];

    // Chuyển hướng người dùng trở lại trang chủ
    header("Location: homeapp.php");
    exit;
} else {
    echo "Authorization code not found";
}
?>
