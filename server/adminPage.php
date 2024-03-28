<?php
include '../database/connect.php';


function findUser($DB, $email)
{
    if (!empty ($email)) {
        $sql = "SELECT user.id, user.email, user.username, permission.permissionName, user.block FROM user INNER JOIN permission ON user.permissionID = permission.permissionID WHERE email = ?";
        $stmt = $DB->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $sql = 'SELECT user.id, user.email, user.username, permission.permissionName, user.block FROM user INNER JOIN permission ON user.permissionID = permission.permissionID';
        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $result;
}

function AD_onloadQuery($DB)
{
    $result["userList"] = findUser($DB, "");
    $stmt = $DB->query("SELECT * FROM music_source_path LIMIT 10");
    $result["musicList"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!isset ($result["userList"]) || !isset ($result["musicList"])) {
        die ("[SQL ERROR] query error");
    }
    return $result;
}


function getMusicById($DB, $id)
{
    $stmt = $DB->prepare("SELECT musicName, musicPath, author, imgPath, gifPath, tag FROM music_source_path WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!isset ($result)) {
        die ("[SQL ERROR] query error");
    }
    return $result;
}


function updateMusic($DB, $data)
{
    $stmt = $DB->prepare("UPDATE music_source_path SET musicName = ?, musicPath = ?, author = ?, imgPath = ?, gifPath = ?, tag = ?, duration = ? WHERE id = ?");
    $updateResult = $stmt->execute([$data["musicName"], $data["musicPath"], $data["musicAuthor"], $data["imgPath"], $data["update-gif-path"], $data["update-music-tag"], $data["duration"], $data["musicId"]]);

    if (!$updateResult) {
        die ("[SQL ERROR] update error");
    }
    return $updateResult;
}

function userAction($DB, $userID, $action)
{
    if (!empty ($userID)) {
        $stmtCHECK = $DB->prepare("SELECT permissionID, block FROM user WHERE id = ?");
        $stmtCHECK->execute([$userID]);
        $CheckResult = $stmtCHECK->fetchAll(PDO::FETCH_ASSOC);

        if (isset ($CheckResult[0])) {
            $permissionID = $CheckResult[0]['permissionID'];
            $blockStatus = $CheckResult[0]['block'];

            if ($permissionID != 1) {
                if ($action == "block") {
                    $stmtAction = $DB->prepare("UPDATE user SET block = !{$blockStatus} WHERE id = ?");
                } else {
                    $stmtAction = $DB->prepare("DELETE FROM user WHERE id = ?");
                }

                $result = $stmtAction->execute([$userID]);
                return ["status" => $result];
            } else {
                return ["status" => -1];
            }
        } else {
            return ["status" => -1];
        }
    }
}


$insertQuery = "INSERT INTO music_source_path (musicName, musicPath, author, imgPath, gifPath, duration) VALUES (?, ?, ?, ?, ?, ?)";
function uploadMusic($data, $connect, $insertQuery)
{
    if (isset ($data["name-music"]) && isset ($data["path-music"])) {
        $nameMusic = $data["name-music"];
        $pathMusic = $data["path-music"];
        $author = $data["author-music"];
        $imgPath = $data["img-path-music"];
        $gifPath = $data["gif-path-music"];
        $uploadMusicDuration = $data["duration"];

        $stmt = $connect->prepare($insertQuery);
        $uploadResult = $stmt->execute([$nameMusic, $pathMusic, $author, $imgPath, $gifPath, $uploadMusicDuration]);
        if (!$uploadResult) {
            return ["status" => false];
        }
        return ["status" => $uploadResult];
    } else
        return ["status" => false];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST["requestCode"]) {
        case 2:
            parse_str($_POST["formData"], $formData);
            echo json_encode(uploadMusic($formData, $connect, $insertQuery));
            break;
        case 1:
            echo json_encode(AD_onloadQuery($connect));
            break;

        case 3:
            if (isset ($_POST['musicID'])) {
                echo json_encode(getMusicById($connect, $_POST["musicID"]));
            }
            break;
        case 4:
            parse_str($_POST["formData"], $formData);

            if (isset ($formData["musicId"]) && isset ($formData["musicName"])) {
                echo json_encode(updateMusic($connect, $formData));
            } else
                echo "null";

            break;
        case 5:
            if (isset ($_POST["userID"])) {
                echo json_encode(userAction($connect, $_POST["userID"], "block"));
            }
            break;
        case 6:
            if (isset ($_POST["userID"])) {
                echo json_encode(userAction($connect, $_POST["userID"], "delete"));
            }
            break;
        case 7:
            if (isset ($_POST["email"])) {
                echo json_encode(findUser($connect, $_POST["email"]));
            }
            break;
        default:
            break;
    }
}
?>