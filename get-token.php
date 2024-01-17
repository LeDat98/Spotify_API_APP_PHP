<?php
session_start();
if (isset($_SESSION['access_token'])) {
    echo json_encode(array('access_token' => $_SESSION['access_token']));
} else {
    echo json_encode(array('error' => 'Access token not found'));
}
?>
