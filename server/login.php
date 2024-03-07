<!-- SERVER -->
<?php
$server_nameDB = "localhost";
$usernameDB = "root";
$passwordDB = "";
$DB = "music_app_db";

$connect = mysqli_connect($server_nameDB, $usernameDB, $passwordDB, $DB);
if(mysqli_connect_error()) {
    die("[SQL] connect Error". mysqli_connect_error());
}


function check($string)
{
    $string = trim($string);
    $string = stripcslashes($string);
    $string = htmlspecialchars($string);

    return $string;
}

$createUserSql = "INSERT INTO user (email, hash, permissionID) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($connect, $createUserSql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["sign-up-email"]) && isset($_POST["sign-up-password"]) && isset($_POST["sign-up-repeat-password"])) {
        $password = check($_POST["sign-up-password"]);
        $email = check($_POST["sign-up-email"]);
        $hash =  password_hash($password, PASSWORD_DEFAULT);
        $permission = 3;

        $createUserParam = mysqli_stmt_bind_param($stmt, "ssi", $email, $hash, $permission);
        echo mysqli_stmt_execute($stmt);
        echo $hash;
    }
}
?>