<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
echo "<p>app is working</p>";


$server_name = 'localhost';
$username = 'root';
$password = '';
$database = 'music_app_db';

$connect = mysqli_connect($server_name, $username, $password, $database);
if (!$connect) {
    die('[sql] Error connect' . mysqli_connect_error());
}
echo '[sql] Connect success';


$result = mysqli_query($connect, 'SELECT * FROM music_source_path');
if (!$result) {
    die('[query] Error' . mysqli_connect_error());
}

$musicArray = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $musicArray[] = $row;
        echo '<p>music name | ' . $row["musicName"] . 'music path | ' . $row["musicPath"] . '</p>';

    }
} else {
    echo 'data is null';
}
?>

<body>
    <?php
    if (!empty($musicArray)) {
        foreach ($musicArray as $data) {
            echo '<audio controls><source src="' . $data['musicPath'] . '" type="audio/mp3"></audio>';
        }
    } else {
        echo '<source src="" type="audio/mp3">';
    }
    ?>
</body>

</html>