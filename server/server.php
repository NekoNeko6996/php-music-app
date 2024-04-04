<?php
include '../database/connect.php';
include '../library/library.php';

session_start();
// check
if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
    Auth($_SESSION['token'], $connect);
}

function convertToArray($Array)
{
    foreach ($Array as &$row) {
        $row = array_values($row);
    }
    return $Array;
}

// query
function findIDUSer($email, $connect)
{
    $stmt = $connect->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $userID = $stmt->fetchColumn();
    if ($userID !== false) {
        return $userID;
    }
    return false;
}

function sortByTag($tag, $connect, $email)
{
    if ($tag == "random") {
        $result = query("SELECT * FROM music_source_path ORDER BY RAND() LIMIT 30", [], $connect)['result'];
    } else if ($tag == "library") {
        $userID = findIDUSer($email, $connect);
        if (!empty($userID)) {
            $result = query("SELECT * FROM music_source_path WHERE id IN (SELECT musicID FROM library WHERE userID = ?)", [$userID], $connect)['result'];
        } else {
            $result = query("SELECT * FROM music_source_path WHERE id IN (SELECT musicID FROM library WHERE userID = -1)", [], $connect)['result'];
        }
    } else {
        $result = query("SELECT * FROM music_source_path WHERE tag LIKE ? LIMIT 30", ["%$tag%"], $connect)['result'];
    }

    return $result;
}

function onloadQuery($connect, $email, $token)
{
    $user = Auth($token, $connect);

    $result["newMusic"] = query('SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 9', [], $connect)['result'];
    $result["top3Music"] = query('SELECT * FROM music_source_path ORDER BY listens DESC LIMIT 3', [], $connect)['result'];
    $result["playlists"] = query('SELECT * FROM music_source_path ORDER BY RAND() LIMIT 10', [], $connect)['result'];
    $result["musicByTag"] = query('SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 40', [], $connect)['result'];
    $result["albumsLoad"] = query('SELECT * FROM albums', [], $connect)['result'];
    $result["library"] = sortByTag("library", $connect, $email);

    if (isset($user['permissionID']) && isset($user['id'])) {
        $result["userAlbumsList"] = query("SELECT id, albumName, createAt, albumImgPath FROM user_albums WHERE userID = ?", [$user['id']], $connect)['result'];
    }

    if (empty($result["newMusic"]) || empty($result["top3Music"]) || empty($result["playlists"]))
        echo 'data is null';

    return $result;
}


function sqlAddLibrary($musicID, $email, $connect)
{
    $userID = findIDUSer($email, $connect);
    if ($userID) {
        $stmt = $connect->prepare("SELECT * FROM library WHERE userID = ? AND musicID = ?");
        $stmt->execute([$userID, $musicID]);
        if ($stmt->rowCount() == 0) {
            $sqlAddLibrary = "INSERT INTO library (userID, musicID) VALUES (?, ?)";
            $stmt = $connect->prepare($sqlAddLibrary);
            $result = $stmt->execute([$userID, $musicID]);
            return $result;
        } else {
            $stmt = $connect->prepare("DELETE FROM library WHERE userID = ? AND musicID = ?");
            $result = $stmt->execute([$userID, $musicID]);
            return "Delete";
        }
    }
    return false;
}

function searchMusic($searchString, $connect)
{
    if (!empty($searchString)) {
        $string = "%$searchString%";
        $stmt = $connect->prepare("SELECT * FROM music_source_path WHERE musicName LIKE ? LIMIT 6");
        $stmt->execute([$string]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

function loadAlbumsList($albumID, $connect, $myAlbum)
{
    if (!empty($albumID)) {
        $sql = "SELECT * FROM music_source_path WHERE id in (SELECT musicID FROM albums_music_list WHERE albumID = ?)";
        if ($myAlbum == 1) {
            $sql = "SELECT * FROM music_source_path WHERE id in (SELECT musicID FROM user_albums_music_list WHERE userAlbumID = ?)";
        }
        $execute = query($sql, [$albumID], $connect);
        $result = $execute['result'];
        return $result;
    }
    return [];
}


function createMyAlbum($token, $createNameAlbum)
{
    $defaultImg = 'assets/img/default.jpg';
    $connect = $GLOBALS['connect'];
    $user = Auth(check($token), $connect);
    if (query("SELECT albumName FROM user_albums WHERE userID = ? AND albumName = ?", [$user['id'], $createNameAlbum], $connect)['numRow'] == 0) {
        if (isset($user['permissionID']) && isset($user['id'])) {
            $sql = "INSERT INTO user_albums (albumName, userID, albumImgPath) VALUES (?, ?, ?)";
            return query($sql, [$createNameAlbum, $user['id'], $defaultImg], $connect)['stmt'];
        }
    }
    return false;
}



function addToMyAlbum($musicID, $albumID, $connect)
{
    if (isset($_SESSION['userID'])) {
        $issetAlbum = query("SELECT * FROM user_albums WHERE id = ? AND userID = ?", [$albumID, $_SESSION['userID']], $connect)['numRow'] == 1;
        $issetMusic = checkIssetMusic($musicID, $connect)['isset'];
        $issetMusicInAlbum = query("SELECT * FROM user_albums_music_list WHERE userAlbumID = ? AND musicID = ?", [$albumID, $musicID], $connect)['numRow'] == 0;

        if ($issetAlbum && $issetMusic && $issetMusicInAlbum) {
            $sql = "INSERT INTO user_albums_music_list SET userAlbumID = ?, musicID = ?";
            $status = query($sql, [$albumID, $musicID], $connect)['stmt'];
            return ['status' => $status, 'newList' => loadAlbumsList($albumID, $connect, true)];
        } else
            return ['status' => false, 'message' => 'music has not been added to your album'];
    } else
        return false;
}


function deleteFromMyAlbum($musicID, $albumID, $connect)
{
    if (isset($_SESSION['userID'])) {
        $issetMusicInAlbum = query("SELECT * FROM user_albums_music_list WHERE musicID = ? AND userAlbumID = ?", [$musicID, $albumID], $connect)['numRow'] == 1;
        $issetAlbum = query("SELECT * FROM user_albums WHERE id = ? AND userID = ?", [$albumID, $_SESSION['userID']], $connect)['numRow'] == 1;
        if ($issetAlbum && $issetMusicInAlbum) {
            $status = query("DELETE FROM user_albums_music_list WHERE musicID = ? AND userAlbumID = ?", [$musicID, $albumID], $connect)['stmt'];
            return ['status' => $status, 'newList' => loadAlbumsList($albumID, $connect, true)];
        } else
            return ['status' => false, 'message' => 'music not found'];
    }
    return ['status' => false, 'message' => 'you must login to delete this'];
}



// ---------------- //
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = [];
    if (isset($_POST["requestCode"])) {
        switch ($_POST["requestCode"]) {
            case 1:
                $response = onloadQuery($connect, $_POST["userEmail"], !empty($_POST['token']) ? $_POST['token'] : "");
                break;
            case 2:
                $response = sortByTag($_POST["data"], $connect, $_POST["userEmail"]);
                break;
            case 3:
                if (isset($_POST["musicID"]) && isset($_POST["userEmail"])) {
                    $response = sqlAddLibrary($_POST["musicID"], $_POST["userEmail"], $connect);
                }
                break;
            case 4:
                if (isset($_POST["searchString"])) {
                    $response = searchMusic(check($_POST["searchString"]), $connect);
                }
                break;
            case 5:
                if (isset($_POST["albumID"])) {
                    $response = loadAlbumsList(check($_POST["albumID"]), $connect, json_decode($_POST['myAlbum']));
                }
                break;

            case 6:
                if (isset($_POST['createNameAlbum']) && isset($_POST['token'])) {
                    $response = createMyAlbum($_POST['token'], $_POST['createNameAlbum']);
                }
                break;
            case 7:
                if (isset($_POST['musicID']) && isset($_POST['albumID']) && isset($_POST['token'])) {
                    $response = addToMyAlbum($_POST['musicID'], $_POST['albumID'], $connect);
                }
                break;
            case 8:
                if (isset($_POST['musicID']) && isset($_POST['albumID'])) {
                    $response = deleteFromMyAlbum($_POST['musicID'], $_POST['albumID'], $connect);
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