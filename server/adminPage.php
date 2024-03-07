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
    $result["userList"] = mysqli_fetch_all(mysqli_query($DB, "SELECT user.id, user.email, permission.permissionName FROM user INNER JOIN permission ON user.permissionID = permission.permissionID"));
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



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST["requestCode"]) {
        case 2:
            if (isset($_POST["name-music"]) && isset($_POST["path-music"])) {
                $nameMusic = $_POST["name-music"];
                $pathMusic = $_POST["path-music"];
                $author = $_POST["author-music"];
                $imgPath = $_POST["img-path-music"];
                $gifPath = $_POST["gif-path-music"];

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
        default:
            break;
    }
}
?>