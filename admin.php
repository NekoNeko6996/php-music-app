<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/admin.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/adminPage.js" defer></script>
  <title>ADMIN DASHBOARD</title>
</head>


<?php
include 'database/connect.php';
include 'library/library.php';

session_start();
if (isset($_SESSION['token'])) {
  if (isset($_SESSION["user"]) && isset($_SESSION["permissionID"]) && $_SESSION["permissionID"] != 3 && $_SESSION["permissionID"] != -1) {
    $permission = $_SESSION["permissionID"];
  }
} else {
  header("Location: home.php");
  exit();
}


$token = $_SESSION['token'];
$userID = query("SELECT id FROM user WHERE loginToken = ?", [$token], $connect);
if (!isset($userID['result'][0]['id'])) {
  echo '[USER] NOT FOUND';
} else {
  $_SESSION['userID'] = $userID['result'][0]['id'];
}
?>



<body>
  <nav class="admin-page-nav">
    <a href="moreInfo.php?action=upload">
      Upload New Music
    </a>
    <a href="home.php">Home</a>
  </nav>
  <div class="music-edit-container">
    <div class="music-box">
      <h2>Music Uploaded By You</h2>
      <table class="show-music-table-container">
        <thead>
          <tr>
            <th></th>
            <th>Music Name</th>
            <th>Author</th>
            <th>Tag</th>
            <th></th>
          </tr>
        </thead>

        <tbody class="uploader-show-music-area">
          <!-- show here -->
        </tbody>
      </table>
    </div>
  </div>

  <?php if ($permission == 1) {
    echo " 
  <div class='user-display'>
    <form method='post' onsubmit='searchUser(event)'>
      <input type='email' name='search-user-input' id='search-user-input' placeholder='input email...'>
      <button type='button' class='normal-btn' onclick='searchUser()'>Search User</button>
      <button type='button' class='normal-btn' onclick='reload()'>Reload</button>
    </form>
    <table id='user-display-table'></table>
  </div>";
  } ?>
</body>

</html>