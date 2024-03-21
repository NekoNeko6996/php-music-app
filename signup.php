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
  if (isset($_POST["sign-up-email"]) && isset($_POST["sign-up-password"]) && isset($_POST["sign-up-repeat-password"]) && isset($_POST["sign-up-username"])) {
    $repeatPassword = check($_POST["sign-up-repeat-password"]);
    $password = check($_POST["sign-up-password"]);
    $email = strtolower(check($_POST["sign-up-email"]));
    $username = check($_POST["sign-up-username"]);

    if ($repeatPassword == $password) {
      $findUser = mysqli_query($connect, "SELECT id FROM user WHERE email = '$email'") or die("[SQL ERROR] find user ERROR" . mysqli_error($connect));

      if (mysqli_num_rows($findUser) == 0) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $permission = 3;

        $createUserParam = mysqli_stmt_bind_param($stmt, "sssi", $email, $username, $hash, $permission);
        $exeResult = mysqli_stmt_execute($stmt);
        if ($exeResult == 1) {
          session_start();
          $_SESSION["user"] = $email;
          $_SESSION["username"] = $username;
          $_SESSION["permissionID"] = $permission;

          header("Location: home.php");
          exit();
        }
      } else {
        echo '<div class="login-error-display">Email already exists!</div>';
      }
    } else {
      echo '<div class="login-error-display">Password and re-enter password do not match!</div>';
    }
  }
}

if (isset($_SESSION["user"]) && isset($_SESSION["permissionID"])) {
  header("Location: home.php");
}
?>

<!-- Client -->

<body>
  <nav>
    <a href="login.php">Login</a>
    <a href="home.php">Home</a>
  </nav>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" id="sign-up-form" class="sign-up-form"
    method="post">
    <h1 class="login-text">Sign Up</h1>
    <label for="sign-up-email">Email</label>
    <input type="email" name="sign-up-email" id="sign-up-email" required />
    <label for="sign-up-username">Username</label>
    <input type="text" name="sign-up-username" id="sign-up-username" required />
    <label for="path-music-input">Password</label>
    <input type="password" name="sign-up-password" id="sign-up-password" required autocomplete />
    <label for="sign-up-repeat-password">Repeat Password</label>
    <input type="password" name="sign-up-repeat-password" id="sign-up-repeat-password" required autocomplete />
    <input type="submit" value="Sign Up" id="submit-sign-up" class="submit-login-signup" />
    <p>Have an account?, <a href="login.php">login now.</a></p>
  </form>
</body>

</html>