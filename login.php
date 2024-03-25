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
include 'database/connect.php';

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

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["login-email"]) && isset($_POST["login-password"])) {
    $password = check($_POST["login-password"]);
    $email = strtolower(check($_POST["login-email"]));

    $result = $connect->query("SELECT userName, hash, permissionID, block FROM user WHERE email = '$email'");
    if($result)
    if ($result->rowCount() > 0) {
      $findUser = $result->fetchAll(PDO::FETCH_ASSOC);

      $hashPassword = $findUser[0]["hash"];
      $username = $findUser[0]["userName"];
      $permissionID = $findUser[0]["permissionID"];
      $blockStatus = $findUser[0]["block"];

      if ($blockStatus != 1) {
        $checkHash = password_verify($password, $hashPassword);
        if ($checkHash) {
          $_SESSION["user"] = $email;
          $_SESSION["username"] = $username;
          $_SESSION["permissionID"] = $permissionID;
        } else {
          echo '<div class="login-error-display">Email or password is incorrect!</div>';
        }
      } else
        echo '<div class="login-error-display">The user has been blocked by the administrator</div>';
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