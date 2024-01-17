<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MusicApp"; 
session_start();
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_POST['id'];
$userPass = $_POST['password'];

$sql = "SELECT Password FROM Users WHERE UserID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['Password'];

    if (password_verify($userPass, $hashed_password)) {
        
        $_SESSION['UserID'] = $userID;
        header("Location: homeapp.php");
        exit();
    }
}

echo "The username or password you entered is incorrect.";
echo "<button onclick=\"window.location.href = 'login.html';\">Return to Login Page</button>";

$stmt->close();
$conn->close();
?>
