<?php
include '../database/connect.php';
include '../library/library.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token'])) {
  $token = $_SESSION['token'];

  $status = destroyToken($token, $connect);
  if ($status) {
    session_destroy();
    echo json_encode(["status" => true]);
  } else
    echo json_encode(["status" => false]);
}
?>