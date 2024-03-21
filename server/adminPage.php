<!-- SERVER -->
<?php
$server_name = "localhost";
$username = "root";
$password = "";
$database = "music_app_db";

$connect = mysqli_connect($server_name, $username, $password, $database);

if (!$connect) {
    die ("[SQL] Error" . mysqli_connect_error());
}


$insertQuery = "INSERT INTO music_source_path (musicName, musicPath, author, imgPath, gifPath, duration) VALUES (?, ?, ?, ?, ?, ?)";

function findUser($DB, $email)
{
    if (!empty ($email))
        $sql = "SELECT user.id, user.email, user.username, permission.permissionName, user.block FROM user INNER JOIN permission ON user.permissionID = permission.permissionID WHERE email = ?";
    else
        $sql = "SELECT user.id, user.email, user.username, permission.permissionName, user.block FROM user INNER JOIN permission ON user.permissionID = permission.permissionID";

    $stmt = mysqli_prepare($DB, $sql);
    if (!empty ($email))
        mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result)) {
        return mysqli_fetch_all($result);
    }
    return [];
}

function AD_onloadQuery($DB)
{
    $result["userList"] = findUser($DB, "");
    $result["musicList"] = mysqli_fetch_all(mysqli_query($DB, "SELECT * FROM music_source_path LIMIT 10"));

    if (!isset ($result["userList"]) || !isset ($result["musicList"])) {
        die ("[SQL ERROR] query error" . mysqli_error($DB));
    }
    return $result;
}

function getMusicById($DB, $id)
{
    $result = mysqli_fetch_all(mysqli_query($DB, "SELECT musicName, musicPath, author, imgPath, gifPath, tag FROM music_source_path WHERE id = '$id'"));
    if (!isset ($result)) {
        die ("[SQL ERROR] query error" . mysqli_error($DB));
    }
    return $result;
}

function updateMusic($DB, $data)
{
    $updateQuery = "UPDATE music_source_path SET musicName = ?, musicPath = ?, author = ?, imgPath = ?, gifPath = ?, tag = ?, duration = ? WHERE id = ?";
    $stmt = mysqli_prepare($DB, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssssssii", $data["musicName"], $data["musicPath"], $data["musicAuthor"], $data["imgPath"], $data["update-gif-path"], $data["update-music-tag"], $data["duration"], $data["musicId"]);
    $updateResult = mysqli_stmt_execute($stmt);
    if (!$updateResult) {
        die ("[SQL ERROR] update error" . mysqli_error($DB));
    }
    return $updateResult;
}
function userAction($DB, $userID, $action)
{
    if (!empty ($userID)) {
        $stmtCHECK = mysqli_prepare($DB, "SELECT permissionID, block FROM user WHERE id = ?");
        $stmtCHECK = mysqli_prepare($DB, "SELECT permissionID, block FROM user WHERE id = ?");
        mysqli_stmt_bind_param($stmtCHECK, "i", $userID);
        mysqli_stmt_execute($stmtCHECK);
        $CheckResult = mysqli_stmt_get_result($stmtCHECK);
        $CheckResult = mysqli_fetch_all($CheckResult);
        if (isset ($CheckResult[0])) {
            $permissionID = $CheckResult[0][0];
            $blockStatus = $CheckResult[0][1];
            if ($permissionID != 1) {
                if ($action == "block") {
                    $stmtAction = "UPDATE user SET block = !{$blockStatus} WHERE id = ?";
                } else {
                    $stmtAction = "DELETE FROM user WHERE id = ?";
                }

                $stmtAction = mysqli_prepare($DB, $stmtAction);
                mysqli_stmt_bind_param($stmtAction, "i", $userID);
                $result = mysqli_stmt_execute($stmtAction);
                return ["status" => $result];
            } else {
                return ["status" => -1];
            }
        } else
            return ["status" => -1];
    }
}


function uploadMusic($data, $connect, $insertQuery)
{
    if (isset ($data["name-music"]) && isset ($data["path-music"])) {
        $nameMusic = $data["name-music"];
        $pathMusic = $data["path-music"];
        $author = $data["author-music"];
        $imgPath = $data["img-path-music"];
        $gifPath = $data["gif-path-music"];
        $uploadMusicDuration = $data["duration"];

        $stmt = mysqli_prepare($connect, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssssi", $nameMusic, $pathMusic, $author, $imgPath, $gifPath, $uploadMusicDuration);

        $uploadResult = mysqli_stmt_execute($stmt);
        if (!$uploadResult) {
            echo "[SQL] Insert ERROR" . mysqli_error($connect);
        }
        return ["status" => $uploadResult];
    }
    else return ["status" => false];
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