<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/signup.js" defer></script>
  <link rel="stylesheet" href="css/signup.css" />
  <title>Login</title>
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
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["login-email"]) && isset($_POST["login-password"])) {
    $password = check($_POST["login-password"]);
    $email = check($_POST["login-email"]);

    $findUser = mysqli_fetch_all(mysqli_query($connect, "SELECT userName, hash FROM user WHERE email = '$email'")) or die("[SQL] find user error". mysqli_error($connect));

    $hashPassword = $findUser[0][1];
    if ($hashPassword) {
      $checkHash = password_verify($password, $hashPassword);
      if ($checkHash) {
        $_SESSION["user"] = $_POST["login-email"];
        $_SESSION["username"] = $findUser[0][0];
        echo "login success";
      } else {
        echo "login false";
      }
    } else {
      echo "email not found";
    }

  }
}


if (isset($_SESSION["user"])) {
  header("Location: home.php");
}
?>

<body>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" id="login-form" method="post">
    <h2>Login</h2>
    <label for="login-email">Email</label>
    <input type="email" name="login-email" id="login-email" required />
    <label for="path-music-input">Password</label>
    <input type="password" name="login-password" id="login-password" required autocomplete />
    <input type="submit" value="Sign Up" class="submit-login-signup" />
  </form>
</body>
</html>