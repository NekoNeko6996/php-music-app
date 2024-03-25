<?php

include '../database/connect.php';

function check($string)
{
    $string = trim($string);
    $string = stripcslashes($string);
    $string = htmlspecialchars($string);

    return $string;
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
    if ($tag == "random"){
        $sqlSortTag = "SELECT * FROM music_source_path ORDER BY RAND() LIMIT 30";
        $stmt = $connect->query($sqlSortTag);
    }
    else if ($tag == "library") {
        $userID = findIDUSer($email, $connect);
        if (!empty ($userID)) {
            $sqlSortTag = "SELECT * FROM music_source_path WHERE id IN (SELECT musicID FROM library WHERE userID = ?)";
            $stmt = $connect->prepare($sqlSortTag);
            $stmt->execute([$userID]);
        } else {
            $sqlSortTag = "SELECT * FROM music_source_path WHERE id IN (SELECT musicID FROM library WHERE userID = -1)";
            $stmt = $connect->query($sqlSortTag);
        }
    } else {
        $sqlSortTag = "SELECT * FROM music_source_path WHERE tag LIKE ? LIMIT 30";
        $stmt = $connect->prepare($sqlSortTag);
        $stmt->execute(["%$tag%"]);
    }

    return convertToArray($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function onloadQuery($connect, $email)
{
    $newMusicStmt = $connect->query('SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 9');
    $top3MusicStmt = $connect->query('SELECT * FROM music_source_path ORDER BY listens DESC LIMIT 3');
    $playlistsStmt = $connect->query('SELECT * FROM music_source_path ORDER BY RAND() LIMIT 10');
    $musicByTagStmt = $connect->query('SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 40');
    $albumsLoadStmt = $connect->query('SELECT * FROM albums');

    if (!$newMusicStmt || !$top3MusicStmt) {
        die ('[query] Error');
    }

    $result["newMusic"] = convertToArray($newMusicStmt->fetchAll(PDO::FETCH_ASSOC));
    $result["top3Music"] = convertToArray($top3MusicStmt->fetchAll(PDO::FETCH_ASSOC));
    $result["playlists"] = convertToArray($playlistsStmt->fetchAll(PDO::FETCH_ASSOC));
    $result["musicByTag"] = convertToArray($musicByTagStmt->fetchAll(PDO::FETCH_ASSOC));
    $result["albumsLoad"] = convertToArray($albumsLoadStmt->fetchAll(PDO::FETCH_ASSOC));
    $result["library"] = convertToArray(sortByTag("library", $connect, $email));

    if (empty ($result["newMusic"]) || empty ($result["top3Music"]) || empty ($result["playlists"]))
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
    if (!empty ($searchString)) {
        $string = "%$searchString%";
        $stmt = $connect->prepare("SELECT * FROM music_source_path WHERE musicName LIKE ? LIMIT 6");
        $stmt->execute([$string]);
        return convertToArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    return [];
}

function loadAlbumsList($albumID, $connect)
{
    if (!empty ($albumID)) {
        $stmt = $connect->prepare("SELECT * FROM music_source_path WHERE id in (SELECT musicID FROM albums_music_list WHERE albumID = ?)");
        $stmt->execute([$albumID]);
        $result = convertToArray($stmt->fetchAll(PDO::FETCH_ASSOC));
        return $result;
    }
    return [];
}

// ---------------- //
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = [];
    if (isset ($_POST["requestCode"])) {
        switch ($_POST["requestCode"]) {
            case 1:
                $response = onloadQuery($connect, $_POST["userEmail"]);
                break;
            case 2:
                $response = sortByTag($_POST["data"], $connect, $_POST["userEmail"]);
                break;
            case 3:
                if (isset ($_POST["musicID"]) && isset ($_POST["userEmail"])) {
                    $response = sqlAddLibrary($_POST["musicID"], $_POST["userEmail"], $connect);
                }
                break;
            case 4:
                if (isset ($_POST["searchString"])) {
                    $response = searchMusic(check($_POST["searchString"]), $connect);
                }
                break;
            case 5:
                if (isset ($_POST["albumID"])) {
                    $response = loadAlbumsList(check($_POST["albumID"]), $connect);
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