<!-- SERVER -->
<?php
$server_name = "localhost";
$username = "root";
$password = "";
$database = "music_app_db";

$connect = mysqli_connect($server_name, $username, $password, $database);

if (!$connect) {
    die("[SQL] Error" . mysqli_connect_error());
}


$insertQuery = "INSERT INTO music_source_path (musicName, musicPath, author, imgPath, gifPath) VALUES (?, ?, ?, ?, ?)";

function AD_onloadQuery($DB)
{
    $result["userList"] = mysqli_fetch_all(mysqli_query($DB, "SELECT user.id, user.email, user.username, permission.permissionName FROM user INNER JOIN permission ON user.permissionID = permission.permissionID"));
    $result["musicList"] = mysqli_fetch_all(mysqli_query($DB, "SELECT * FROM music_source_path LIMIT 10"));

    if (!isset($result["userList"]) || !isset($result["musicList"])) {
        die("[SQL ERROR] query error" . mysqli_error($DB));
    }
    return $result;
}

function getMusicById($DB, $id)
{
    $result = mysqli_fetch_all(mysqli_query($DB, "SELECT musicName, musicPath, author, imgPath, gifPath, tag FROM music_source_path WHERE id = '$id'"));
    if (!isset($result)) {
        die("[SQL ERROR] query error" . mysqli_error($DB));
    }
    return $result;
}

function updateMusic($DB, $data)    
{   
    $updateQuery = "UPDATE music_source_path SET musicName = ?, musicPath = ?, author = ?, imgPath = ?, gifPath = ?, tag = ? WHERE id = ?";
    $stmt = mysqli_prepare($DB, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssssssi", $data["musicName"], $data["musicPath"], $data["musicAuthor"], $data["imgPath"], $data["update-gif-path"], $data["update-music-tag"], $data["musicId"]);
    $updateResult = mysqli_stmt_execute($stmt);
    if (!$updateResult) {
        die("[SQL ERROR] update error" . mysqli_error($DB));
    }
    return $updateResult;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST["requestCode"]) {
        case 2:
            parse_str($_POST["formData"], $formData);
            if (isset($formData["name-music"]) && isset($formData["path-music"])) {
                $nameMusic = $formData["name-music"];
                $pathMusic = $formData["path-music"];
                $author = $formData["author-music"];
                $imgPath = $formData["img-path-music"];
                $gifPath = $formData["gif-path-music"];

                $stmt = mysqli_prepare($connect, $insertQuery);
                mysqli_stmt_bind_param($stmt, "sssss", $nameMusic, $pathMusic, $author, $imgPath, $gifPath);

                $uploadResult = mysqli_stmt_execute($stmt);
                if (!$uploadResult) {
                    echo "[SQL] Insert ERROR" . mysqli_error($connect);
                }

                echo json_encode(["status" => true]);
            }
            break;
        case 1:
            echo json_encode(AD_onloadQuery($connect));
            break;

        case 3:
            if (isset($_POST['musicID'])) {
                echo json_encode(getMusicById($connect, $_POST["musicID"]));
            }
            break;
        case 4:
            parse_str($_POST["formData"], $formData);

            if (isset($formData["musicId"]) && isset($formData["musicName"])) {
                echo json_encode(updateMusic($connect, $formData));
            } else
                echo "null";

            break;
        default:
            break;
    }
}
?>