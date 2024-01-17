<?php
// 데이터베이스 연결 정보 - 로컬 데이터베이스 설정에 맞게 수정
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

// POST 요청에서 데이터 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_info"])) {
    $useremail = $_POST['email'];
    $confirm_email = $_POST['email_confirm'];

    // 이메일 주소 일치 확인
    if($useremail != $confirm_email) {
        echo "입력한 이메일 주소가 일치하지 않습니다!";
        exit();
    }

    // 이메일 존재 여부 확인
    $email_check_query = "SELECT * FROM Users WHERE Email = ?";
    $email_check_stmt = $conn->prepare($email_check_query);
    $email_check_stmt->bind_param("s", $useremail);
    $email_check_stmt->execute();
    $email_check_result = $email_check_stmt->get_result();

    if ($email_check_result->num_rows == 0) {
        echo "등록되지 않은 이메일 주소입니다!";
        $email_check_stmt->close();
        exit();
    }
    $email_check_stmt->close();

    // 이메일이 존재하는 경우, 세션에 저장하고 다음 페이지로 이동
    session_start();
    $_SESSION["email"] = $useremail;
    header("Location: reset_password2.php");
    exit();
} else {
    // 'submit_info'가 설정되지 않았거나 POST 요청이 아닌 경우
    if (isset($email_check_stmt)) {
        $email_check_stmt->close();
    }
}

// 데이터베이스 연결 종료
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール確認フォーム</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: inline-block;
            width: 200px;
            text-align: right;
            margin-right: 10px;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #5c87b2;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4a6d94;
        }
    </style>
</head>
<body>
    <form method="post">
        <h1>登録されているメールアドレスを入力してください</h1>
        <div>
            <label for="email">メールアドレス:</label>
            <input type="text" name="email" id="email" required>
        </div>
        <div>
            <label for="email_confirm">メールアドレス確認:</label>
            <input type="text" name="email_confirm" id="email_confirm" required>
        </div>
        <div>
            <input type="submit" name="submit_info" value="確認">
        </div>
    </form>
</body>
</html>
