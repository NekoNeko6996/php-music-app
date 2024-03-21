<!-- Server -->
<?php
$server_name = 'localhost';
$username = 'root';
$password = '';
$database = 'music_app_db';

$DB = mysqli_connect($server_name, $username, $password, $database);
if (!$DB) {
    die('[sql] Error connect' . mysqli_connect_error());
}

function check($string)
{
  $string = trim($string);
  $string = stripcslashes($string);
  $string = htmlspecialchars($string);

  return $string;
}

// query
function findIDUSer($email, $DB)
{
    $userID = mysqli_query($DB, "SELECT id FROM user WHERE email = '$email'");
    if (mysqli_num_rows($userID) != 0) {
        return mysqli_fetch_array($userID)[0][0];
    }
    return false;
}

function sortByTag($tag, $DB, $email)
{
    if ($tag == "random")
        $sqlSortTag = "SELECT * FROM music_source_path ORDER BY RAND() LIMIT 30";
    else if ($tag == "library") {
        $userID = findIDUSer($email, $DB);
        if (!empty($userID)) {
            $sqlSortTag = "SELECT * FROM music_source_path WHERE id IN (SELECT musicID FROM library WHERE userID = '$userID')";
        } else {
            $sqlSortTag = "SELECT * FROM music_source_path WHERE id IN (SELECT musicID FROM library WHERE userID = -1)";
        }
    } else
        $sqlSortTag = "SELECT * FROM music_source_path WHERE tag LIKE '%$tag%' LIMIT 30";

    $result = mysqli_query($DB, $sqlSortTag);
    $result = mysqli_fetch_all($result);
    return $result;
}

function onloadQuery($DB, $email)
{
    $result["newMusic"] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 9');
    $result['top3Music'] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY listens DESC LIMIT 3');
    $result['playlists'] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY RAND() LIMIT 10');
    $result['musicByTag'] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 30');
    $result['albumsLoad'] = mysqli_query($DB, 'SELECT * FROM albums');

    if (!$result["newMusic"] || !$result["top3Music"]) {
        die('[query] Error' . mysqli_connect_error());
    }

    $result["newMusic"] = mysqli_fetch_all($result["newMusic"]);
    $result["top3Music"] = mysqli_fetch_all($result["top3Music"]);
    $result["playlists"] = mysqli_fetch_all($result["playlists"]);
    $result["musicByTag"] = mysqli_fetch_all($result["musicByTag"]);
    $result["albumsLoad"] = mysqli_fetch_all($result["albumsLoad"]);
    $result["library"] = sortByTag("library", $DB, $email);

    if (empty($result["newMusic"]) || empty($result["top3Music"]) || empty($result["playlists"]))
        echo 'data is null';
    return $result;
}


function sqlAddLibrary($musicID, $email, $DB)
{
    $userID = findIDUSer($email, $DB);
    if ($userID) {
        if (mysqli_num_rows(mysqli_query($DB, "SELECT * FROM library WHERE userID = '$userID' AND musicID = '$musicID'")) == 0) {
            $sqlAddLibrary = "INSERT INTO library (userID, musicID) VALUES (?, ?)";
            $stmt = mysqli_prepare($DB, $sqlAddLibrary);
            mysqli_stmt_bind_param($stmt, "ss", $userID, $musicID);
            $result = mysqli_stmt_execute($stmt);
            return $result;
        } else {
            mysqli_query($DB, "DELETE FROM library WHERE userID = '$userID' AND musicID = '$musicID'");
            return "Delete";
        }
    }
    return false;
}

function searchMusic($searchString, $DB)
{
    if (!empty($searchString)) {
        $string = "%$searchString%";
        $searchQuery = "SELECT * FROM music_source_path WHERE musicName LIKE ? LIMIT 6";
        $stmt = mysqli_prepare($DB, $searchQuery);
        mysqli_stmt_bind_param($stmt, "s", $string);
        mysqli_stmt_execute($stmt) or die(mysqli_error($DB));
        return mysqli_fetch_all(mysqli_stmt_get_result($stmt));
    }
    return [];
}

function loadAlbumsList($albumID, $DB) {
    if (!empty($albumID)) {
        $sql = "SELECT * FROM music_source_path WHERE id in (SELECT musicID FROM albums_music_list WHERE albumID = ?)";
        $stmt = mysqli_prepare($DB, $sql);
        mysqli_stmt_bind_param($stmt,"i", $albumID);
        mysqli_execute($stmt);
        $result = mysqli_fetch_all(mysqli_stmt_get_result($stmt));
        return $result;
    }
    return [];
}

// ---------------- //
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = [];
    if (isset($_POST["requestCode"])) {
        switch ($_POST["requestCode"]) {
            case 1:
                $response = onloadQuery($DB, $_POST["userEmail"]);
                break;
            case 2:
                $response = sortByTag($_POST["data"], $DB, $_POST["userEmail"]);

            case 3:
                if (isset($_POST["musicID"]) && isset($_POST["userEmail"])) {
                    $response = sqlAddLibrary($_POST["musicID"], $_POST["userEmail"], $DB);
                }
                break;
            case 4:
                if (isset($_POST["searchString"])) {
                    $response = searchMusic(check($_POST["searchString"]), $DB);
                }
                break;
            case 5:
                if (isset($_POST["albumID"])) {
                    $response = loadAlbumsList(check($_POST["albumID"]), $DB);
                }
                break;
            default:
                break;
        }
        echo json_encode($response);
    } else {
        echo 0;
    }
}

?>