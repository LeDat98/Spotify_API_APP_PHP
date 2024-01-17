<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
session_start();

if (!isset($_SESSION["email"])) {
    echo "error.";
    exit();
}

// 데이터베이스 연결 정보
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MusicApp";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    // PHPMailer 로드
    $current_dir = dirname(__FILE__);
    require($current_dir . '/vendor/autoload.php');

    date_default_timezone_set('Asia/Tokyo');
    mb_language('japanese');
    mb_internal_encoding('utf-8');

    $mail = new PHPMailer(true);
    $mail->CharSet = PHPMailer::CHARSET_UTF8;

    // SMTP 설정
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'wngud0012@gmail.com'; // 이메일 주소 입력
    $mail->Password = 'tvmx tbay xccr lbls'; // 이메일 비밀번호 입력
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom('wngud0012@gmail.com', 'juhyeong');
    $mail->addReplyTo('wngud0012@gmail.com', 'juhyeong');
    $mail->addAddress($_SESSION["email"]);
    $mail->Subject = '確認メール';
    $mail->isHTML(true);

    // 토큰과 함께 현재 시간을 데이터베이스에 저장
    $token = bin2hex(random_bytes(32));
    $expiry_time = time() + (30 * 60); // 현재 시간으로부터 30분 후
    $update_token_query = "UPDATE Users SET reset_token = ?, token_expiry = ? WHERE Email = ?";
    $stmt = $conn->prepare($update_token_query);
    $stmt->bind_param("sis", $token, $expiry_time, $_SESSION["email"]);
    $stmt->execute();
    $stmt->close();

    echo "<div style='text-align: center; margin-top: 20%;'>"; // Centering the text
    // 이메일 본문 생성
    $resetLink = 'http://localhost/MusicApp/find_password.php?token=' . $token;
    $mail->Body = "<p>パスワード初期化のためのリンクです。</p><p><a href='{$resetLink}'>パスワード初期化</a></p>";

    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "<span style='color: red;'>パスワード初期化リンクを送信しました。</span><br>";
        echo "メールアドレス: " . $_SESSION["email"];
        session_unset();
        session_destroy();
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

$conn->close();
?>
