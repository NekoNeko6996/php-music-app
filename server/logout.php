<?php
include '../database/connect.php';
include '../library/library.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['token'] && isset($_SESSION['token'])) {
  $token = $_POST['token'];
  $email = $_POST['userEmail'];

  $status = destroyToken($token, $email, $connect);
  if ($status) {
    session_destroy();
    echo json_encode(["status" => true]);
  } else
    echo json_encode(["status" => false]);
}
?>