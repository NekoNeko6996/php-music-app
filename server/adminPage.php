<?php
include '../database/connect.php';
include '../library/library.php';

session_start();

function findUser($DB, $email)
{
    if (!empty($email)) {
        $sql = "SELECT user.id, user.email, user.username, permission.permissionName, user.block FROM user INNER JOIN permission ON user.permissionID = permission.permissionID WHERE email = ?";
        $result = query($sql, [$email], $DB)['result'];
    } else {
        $sql = 'SELECT user.id, user.email, user.username, permission.permissionName, user.block FROM user INNER JOIN permission ON user.permissionID = permission.permissionID';
        $result = query($sql, [], $DB)['result'];
    }
    return $result;
}

function AD_onloadQuery($DB)
{
    if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
        echo $_SESSION['userID'];
        return false;
    }
    $userID = $_SESSION['userID'];
    $result["userList"] = findUser($DB, "");
    $result["musicList"] = query("SELECT * FROM music_source_path WHERE uploadBy = ?", [$userID], $DB)['result'];
    return $result;
}

function updateMusic($DB, $data)
{
    $updateResult = query("UPDATE music_source_path SET musicName = ?, author = ?, tag = ? WHERE id = ?", [$data["musicName"], $data["musicAuthor"], $data["update-music-tag"], $data["musicId"]], $DB)['stmt'];
    return $updateResult;
}

function userAction($DB, $userID, $action)
{
    if (!empty($userID)) {
        $CheckResult = query("SELECT permissionID, block FROM user WHERE id = ?", [$userID], $DB)['result'];

        if (isset($CheckResult[0])) {
            $permissionID = $CheckResult[0]['permissionID'];
            $blockStatus = $CheckResult[0]['block'];

            if ($permissionID != 1) {
                if ($action == "block") {
                    $stmtAction = "UPDATE user SET block = !{$blockStatus} WHERE id = ?";
                } else {
                    $stmtAction = "DELETE FROM user WHERE id = ?";
                }

                $result = query($stmtAction, [$userID], $DB)['stmt'];
                return ["status" => $result];
            } else {
                return ["status" => -1];
            }
        } else {
            return ["status" => -1];
        }
    }
}



function uploadMusic($data, $connect)
{
    if (isset($data["musicName"])) {
        $insertQuery = "INSERT INTO music_source_path (musicName, author, imgPath, tag, uploadBy) VALUES (?, ?, ?, ?, ?)";
        $nameMusic = $data["musicName"];
        $author = $data["musicAuthor"];
        $tag = $data['update-music-tag'];
        $imgPath = 'music/img/default.jpg';

        if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
            echo $_SESSION['userID'];
            return false;
        }
        $userID = $_SESSION['userID'];

        $uploadResult = query($insertQuery, [$nameMusic, $author, $imgPath, $tag, $userID], $connect)['stmt'];
        if (!$uploadResult) {
            return false;
        }
        $findNewID = query("SELECT id FROM music_source_path WHERE musicName = ? AND author = ? AND tag = ?", [$nameMusic, $author, $tag], $connect);
        if ($findNewID['numRow'] == 1) {
            $_SESSION['newUploadMusicID'] = $findNewID['result'][0]['id'];
            return true;
        } else {
            return false;
        }
    } else
        return false;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["requestCode"]))
        switch ($_POST["requestCode"]) {
            case 2:
                parse_str($_POST["formData"], $formData);
                echo json_encode(uploadMusic($formData, $connect));
                break;
            case 1:
                echo json_encode(AD_onloadQuery($connect));
                break;
            case 4:
                parse_str($_POST["formData"], $formData);

                if (isset($formData["musicId"]) && isset($formData["musicName"])) {
                    echo json_encode(updateMusic($connect, $formData));
                } else
                    echo "null";

                break;
            case 5:
                if (isset($_POST["userID"])) {
                    echo json_encode(userAction($connect, $_POST["userID"], "block"));
                }
                break;
            case 6:
                if (isset($_POST["userID"])) {
                    echo json_encode(userAction($connect, $_POST["userID"], "delete"));
                }
                break;
            case 7:
                if (isset($_POST["email"])) {
                    echo json_encode(findUser($connect, $_POST["email"]));
                }
                break;
            default:
                break;
        }
}
?>