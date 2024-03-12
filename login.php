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

    $result = mysqli_query($connect, "SELECT userName, hash, permissionID FROM user WHERE email = '$email'") or die(mysqli_error($connect));

    if (mysqli_num_rows($result) > 0) {
      $findUser = mysqli_fetch_all($result);

      $hashPassword = $findUser[0][1];
      if ($hashPassword) {
        $checkHash = password_verify($password, $hashPassword);
        if ($checkHash) {
          $_SESSION["user"] = $_POST["login-email"];
          $_SESSION["username"] = $findUser[0][0];
          $_SESSION["permissionID"] = $findUser[0][2];
        } else {
          echo '<div class="login-error-display">Email or password is incorrect!</div>';
        }
      } else
        echo '<div class="login-error-display">Login Error</div>';
    } else
      echo '<div class="login-error-display">Email not found!</div>';
  }
}

if (isset($_SESSION["user"]) && isset($_SESSION["permissionID"])) {
  header("Location: home.php");
}
?>

<body>
  <nav>
    <a href="signup.php">Sign Up</a>
    <a href="home.php">Home</a>
  </nav>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" id="login-form" class="sign-up-form"
    method="post">
    <h1 class="login-text">Welcome Back</h1>
    <label for="login-email">Email</label>
    <input type="email" name="login-email" id="login-email" required />
    <label for="path-music-input">Password</label>
    <input type="password" name="login-password" id="login-password" required autocomplete />
    <input type="submit" value="Login" class="submit-login-signup" />
    <p>Don't have an account?, <a href="signup.php">register now.</a></p>
  </form>
</body>

</html>