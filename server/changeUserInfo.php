<?php
include '../database/connect.php';
include '../library/library.php';

session_start();

if (isset($_SESSION['token'])) {
    $USER = Auth($_SESSION['token'], $connect);

    if ($USER && isset($_SERVER['REQUEST_METHOD']) == "POST") {
        if (isset($_POST['requestCode']) && $_POST['requestCode'] == 1 && isset($_POST['newUserName'])) {
            $newUserName = check($_POST['newUserName']);

            $changeNameResult = query("UPDATE user SET userName = ? WHERE loginToken = ?", [$newUserName, $_SESSION['token']], $connect)['stmt'];
            if ($changeNameResult)
                $_SESSION["username"] = $newUserName;
            echo json_encode(["status" => $changeNameResult]);
        }
        if (isset($_POST['requestCode']) && $_POST['requestCode'] == 2 && isset($_POST['password']) && isset($_POST['newPassword'])) {
            $password = $_POST['password'];
            $newPassword = $_POST['newPassword'];

            $passCheck = query("SELECT hash, id FROM user WHERE loginToken = ?", [$_SESSION['token']], $connect)['result'];
            if (isset($passCheck[0]['id'])) {
                if (password_verify($password, $passCheck[0]["hash"])) {
                    $changePasswordResult = query("UPDATE user SET hash = ? WHERE id = ?", [password_hash($newPassword, PASSWORD_DEFAULT), $passCheck[0]['id']], $connect)['stmt'];

                    echo json_encode(['status' => $changePasswordResult]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Wrong password']);
                }
            }

        }
    }

} else {
    echo 'you don\'t have permission';
}


?>