
<?php
// 데이터베이스 연결 정보 - 로컬 데이터베이스 설정에 맞게 수정
$servername = "localhost"; // 데이터베이스 서버 주소
$username = "root"; // 데이터베이스 사용자 이름
$password = ""; // 데이터베이스 사용자 비밀번호
$dbname = "MusicApp"; // 데이터베이스 이름

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST 요청에서 데이터 가져오기
$fullname = $_POST['fullname'];
$userpass = $_POST['password'];
$confirm_pass = $_POST['confirm_password'];
$useremail = $_POST['email'];

// 비밀번호 일치 확인
if($userpass != $confirm_pass) {
    echo "The passwords do not match!";
    exit();
}

// 비밀번호 해시 생성
$hashed_password = password_hash($userpass, PASSWORD_DEFAULT);

// 아이디 중복 확인
$id_check_query = "SELECT * FROM Users WHERE UserID = ?";
$id_check_stmt = $conn->prepare($id_check_query);
$id_check_stmt->bind_param("s", $fullname);
$id_check_stmt->execute();
$id_check_result = $id_check_stmt->get_result();

if ($id_check_result->num_rows > 0) {
    echo "This username is already taken!";
    exit();
}

// 이메일 중복 확인
$email_check_query = "SELECT * FROM Users WHERE Email = ?";
$email_check_stmt = $conn->prepare($email_check_query);
$email_check_stmt->bind_param("s", $useremail);
$email_check_stmt->execute();
$email_check_result = $email_check_stmt->get_result();

if ($email_check_result->num_rows > 0) {
    echo "This email is already registered!";
    exit();
}

// TODO: 추가적인 데이터 유효성 검사 수행

// SQL을 통해 데이터베이스에 사용자 정보 삽입
$sql = "INSERT INTO Users (UserID, Password, Email) VALUES (?, ?, ?)";

// Prepared statement를 사용하여 SQL 인젝션 방지
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fullname, $hashed_password, $useremail); // 해시된 비밀번호를 사용

// 쿼리 실행
if ($stmt->execute()) {
    echo "New record created successfully";
    echo "<button onclick=\"window.location.href = 'login.html';\">Go to Login Page</button>";

} else {
    echo "Error: " . $stmt->error;
    echo "<button onclick=\"window.location.href = 'Registration.html';\">Return to Registration Page</button>";

}

// 데이터베이스 연결 종료
$id_check_stmt->close();
$email_check_stmt->close();
$stmt->close();
$conn->close();
?>