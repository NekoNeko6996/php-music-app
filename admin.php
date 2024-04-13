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
$token = $_SESSION['token'];

if (isset($token)) {
  $userInfo = query("SELECT permissionID FROM user WHERE loginToken = ?", [$token], $connect)['result'];

  if (isset($userInfo[0]) && $userInfo[0]['permissionID'] != 3 && $userInfo[0]['permissionID'] != -1) {
    $permission = $userInfo[0]['permissionID'];
  }
} else {
  header("Location: home.php");
  exit();
}
?>



<body>
  <nav class="admin-page-nav">
    <div>
      <img src="assets/logo/logo.png" alt="logo">
      <p>𝓜𝓾𝓼𝓲𝓬 𝓐𝓹𝓹</p>
    </div>
    <div>
      <a href="moreInfo.php?action=upload">
        Upload New Music
      </a>
      <a href="home.php">Home</a>
    </div>
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
    <div class="pagination-container">

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