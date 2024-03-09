<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/signup.js" defer></script>
  <link rel="stylesheet" href="css/signup.css" />
  <title>sign up</title>
</head>

<?php
$server_nameDB = "localhost";
$usernameDB = "root";
$passwordDB = "";
$DB = "music_app_db";

$connect = mysqli_connect($server_nameDB, $usernameDB, $passwordDB, $DB);
if (mysqli_connect_error()) {
  die("[SQL] connect Error" . mysqli_connect_error());
}


function check($string)
{
  $string = trim($string);
  $string = stripcslashes($string);
  $string = htmlspecialchars($string);

  return $string;
}

$createUserSql = "INSERT INTO user (email, userName, hash, permissionID) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($connect, $createUserSql);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["sign-up-email"]) && isset($_POST["sign-up-password"]) && isset($_POST["sign-up-repeat-password"])) {
    $password = check($_POST["sign-up-password"]);
    $email = check($_POST["sign-up-email"]);
    $username = check($_POST["sign-up-username"]);

    $findUser = mysqli_query($connect, "SELECT id FROM user WHERE email = '$email'") or die("[SQL ERROR] find user ERROR" . mysqli_error($connect));

    if (mysqli_num_rows($findUser) == 0) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $permission = 3;

      $createUserParam = mysqli_stmt_bind_param($stmt, "sssi", $email, $username, $hash, $permission);
      echo mysqli_stmt_execute($stmt);
    } else {
      echo "USER is exits";
    }
  }
}
?>

<!-- Client -->

<body>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" id="sign-up-form" method="post">
    <h2>Sign Up</h2>
    <label for="sign-up-email">Email</label>
    <input type="email" name="sign-up-email" id="sign-up-email" required />
    <label for="sign-up-username">Username</label>
    <input type="text" name="sign-up-username" id="sign-up-username" required />
    <label for="path-music-input">Password</label>
    <input type="password" name="sign-up-password" id="sign-up-password" required autocomplete />
    <label for="sign-up-repeat-password">Repeat Password</label>
    <input type="password" name="sign-up-repeat-password" id="sign-up-repeat-password" autocomplete />
    <input type="submit" value="Sign Up" id="submit-sign-up" />
  </form>
</body>

</html>