<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="js/signup.js" defer></script>
  <link rel="stylesheet" href="css/signup.css" />
  <title>Login</title>
</head>


<?php
include 'database/connect.php';
include 'library/library.php';


session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["login-email"]) && isset($_POST["login-password"])) {
    $password = check($_POST["login-password"]);
    $email = strtolower(check($_POST["login-email"]));

    $result = query("SELECT id, userName, hash, permissionID, loginToken, block FROM user WHERE email = ?", [$email], $connect);
    if ($result)
      if ($result['numRow'] == 1) {
        $findUser = $result['result'][0];
        $hashPassword = $findUser["hash"];
        $username = $findUser["userName"];
        $permissionID = $findUser["permissionID"];
        $blockStatus = $findUser["block"];
        $userID = $findUser["id"];

        if ($blockStatus != 1) {
          $checkHash = password_verify($password, $hashPassword);
          if ($checkHash) {
            $token = createToken(64);

            $_SESSION["username"] = $username;
            $_SESSION["permissionID"] = $permissionID;
            $_SESSION["token"] = $token;
            $_SESSION["userID"] = $userID;

            query("UPDATE user SET loginToken = ? WHERE email = ?", [$token, $email], $connect);

          } else {
            echo '<div class="login-error-display">Email or password is incorrect!</div>';
          }
        } else
          echo '<div class="login-error-display">The user has been blocked by the administrator</div>';
      } else
        echo '<div class="login-error-display">Email not found!</div>';
  }
}

if (isset($_SESSION["token"]) && !empty($_SESSION["token"])) {
  header("Location: home.php");
}
?>

<body>
  <nav>
    <div>
      <img src="assets/logo/logo.png" alt="logo">
      <p>𝓜𝓾𝓼𝓲𝓬 𝓐𝓹𝓹</p>
    </div>
    <div>
      <a href="signup.php">Sign Up</a>
      <a href="home.php">Home</a>
    </div>
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