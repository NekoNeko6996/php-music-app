<?php
include '../database/connect.php';
include '../library/library.php';

session_start();
if (!isset($_SESSION["token"])) {
    header("Location: ../home.php");
    exit();
}

$token = $_SESSION["token"];
$findUser_toDe = query("SELECT id, permissionID FROM user WHERE loginToken = ?", [$token], $connect);
if (isset($findUser_toDe['result'][0]['id'])) {
    $musicUploadByR = query("SELECT uploadBy FROM music_source_path WHERE id = ?", [$_GET['id']], $connect);
    if (isset($musicUploadByR['result'][0]['uploadBy'])) {
        if ($musicUploadByR['result'][0]['uploadBy'] == $findUser_toDe['result'][0]['id']) {
            if (query("DELETE FROM music_source_path WHERE id = ?", [$_GET['id']], $connect)['stmt']) {
                echo '[SUCCESS] Delete music success';
            }
        } else {
            echo '[ERROR] You Can\'t Delete this music';
        }
    } else {
        echo '[ERROR] Music Not Found';
    }
} else {
    echo '[ERROR] User Not Found';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <button type="button" title="roll back" onclick="window.location.href = '../admin.php'">Back To Admin
        Page</button>
</body>

</html>