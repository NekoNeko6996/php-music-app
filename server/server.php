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




// query
function onloadQuery($DB)
{
    $result["newMusic"] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY timeUpload DESC LIMIT 9');
    $result['top3Music'] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY listens DESC LIMIT 3');
    $result['playlists'] = mysqli_query($DB, 'SELECT * FROM music_source_path ORDER BY RAND() LIMIT 10');
    $result['musicByTag'] = mysqli_query($DB, 'SELECT * FROM music_source_path LIMIT 30');


    if (!$result["newMusic"] || !$result["top3Music"]) {
        die('[query] Error' . mysqli_connect_error());
    }

    $result["newMusic"] = mysqli_fetch_all($result["newMusic"]);
    $result["top3Music"] = mysqli_fetch_all($result["top3Music"]);
    $result["playlists"] = mysqli_fetch_all($result["playlists"]);
    $result["musicByTag"] = mysqli_fetch_all($result["musicByTag"]);

    if (empty($result["newMusic"]) || empty($result["top3Music"]) || empty($result["playlists"]))
        echo 'data is null';
    return $result;
}

function sortByTag($tag, $DB)
{
    if ($tag == "random")
        $sqlSortTag = "SELECT * FROM music_source_path ORDER BY RAND() LIMIT 30";
    else
        $sqlSortTag = "SELECT * FROM music_source_path WHERE tag LIKE '%$tag%' LIMIT 30";

    $result = mysqli_query($DB, $sqlSortTag);
    $result = mysqli_fetch_all($result);
    return $result;
}


// ---------------- //
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = [];
    if (isset($_POST["requestCode"])) {
        switch ($_POST["requestCode"]) {
            case 1:
                $response = json_encode(onloadQuery($DB));
                break;
            case 2:
                $response = json_encode(sortByTag($_POST["data"], $DB));
            default:
                break;
        }
        echo $response;
    } else {
        echo 0;
    }
}


?>