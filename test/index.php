<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
include '../library/library.php';
include '../database/connect.php';
include '../auth/AuthUser.php';

echo "<p>app is working</p>";

$server_name = 'localhost';
$username = 'root';
$password = '';
$database = 'music_app_db';

$connect = mysqli_connect($server_name, $username, $password, $database);
if (!$connect) {
    die ('[sql] Error connect' . mysqli_connect_error());
}
echo '[sql] Connect success';


$result = mysqli_query($connect, 'SELECT * FROM music_source_path');
if (!$result) {
    die ('[query] Error' . mysqli_connect_error());
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
    $DATABASE = new DATABASE($connect);

    // var_dump($DATABASE->select("SELECT * FROM user WHERE email = ?", ["nam@gmail.com"]));
    
    echo '<br>';
    echo $_SERVER['REMOTE_ADDR'] . '<br>';
    echo $_SERVER['HTTP_USER_AGENT'] . '<br>';

    echo Auth("e40034e2ebc1cd1e6d02c21afc144d6751d3a6140e4be7450ed2f53708d682a5", $connect);
    ?>


</body>

</html>