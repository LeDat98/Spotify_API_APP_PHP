<?php
// 데이터베이스 연결 정보
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MusicApp";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 링크 접근 시 유효성 검사
// 예를 들어, find_password.php 파일에서
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $current_time = time();

    // 데이터베이스에서 토큰과 만료 시간을 가져옵니다
    $query = "SELECT reset_token, token_expiry FROM Users WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($current_time > $row['token_expiry']) {
            // 시간 초과
            echo "이 링크는 만료되었습니다.";
            exit();
        }
        // 링크가 유효합니다. 여기에 비밀번호 재설정 로직을 구현합니다.
    } else {
        echo "유효하지 않은 요청입니다.";
    }
    $stmt->close();
}

$passwordChanged = false; // 비밀번호 변경 여부를 확인하기 위한 변수

// POST 요청 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $useremail = $_POST['email'];
    $new_password = $_POST['new_password']; // 새 비밀번호
    $confirm_password = $_POST['confirm_password']; // 새 비밀번호 확인

    // 새 비밀번호와 확인 비밀번호가 일치하는지 확인
    if ($new_password !== $confirm_password) {
        echo "新しいパスワードと確認パスワードが一致しません！";
    } else {
        // 새 비밀번호 해시 생성
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 새 비밀번호를 데이터베이스에 업데이트
        $update_query = "UPDATE Users SET Password = ? WHERE Email = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $hashed_new_password, $useremail); // 해시된 새 비밀번호 사용

        if ($stmt->execute()) {
            echo "パスワードが正常に変更されました！";
            $passwordChanged = true; // 비밀번호가 변경되었음을 표시
        } else {
            echo "パスワードの変更に失敗しました: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
<style>
    form div {
        margin-bottom: 15px;
    }
    label {
        display: inline-block;
        width: 150px;
        text-align: right;
        margin-right: 10px;
    }
</style>

<!DOCTYPE html>
<html>
<head>
    <title>비밀번호 재설정</title>
</head>
<body>
    <?php if (!$passwordChanged): ?>
        <h1>パスワードのリセット</h1>
        <form method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required><br>
            <label for="new_password">新しいパスワード:</label>
            <input type="password" name="new_password" id="new_password" required><br>
            <label for="confirm_password">パスワードの確認:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>
            <input type="submit" value="パスワードの変更">
        </form>
    <?php else: ?>
        <button onclick="window.location.href='login.html'">Go to the login page</button>
    <?php endif; ?>
</body>
</html>
