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
session_start();
if (isset ($_SESSION["user"]) && isset ($_SESSION["permissionID"]) && $_SESSION["permissionID"] != 3 && $_SESSION["permissionID"] != -1) {
  $permission = $_SESSION["permissionID"];
} else {
  header("Location: home.php");
  exit();
}
?>

<body>
  <nav class="admin-page-nav">
    <a href="home.php">Home</a>
  </nav>
  <div class="music-edit-container">
    <!-- <div class="add-new-music">
      <form action="" id="uploadMusic-form" method="post" onsubmit="uploadMusic(event)">
        <h2>Upload Music</h2>
        <label for="name-music-input">Name Music:</label>
        <input type="text" name="name-music" id="name-music-input" required />
        <label for="path-music-input">Path:</label>
        <input type="text" name="path-music" id="path-music-input" required />
        <label for="author-music-input">Author:</label>
        <input type="text" name="author-music" id="author-music-input" />
        <label for="img-path-music-input">Image Path:</label>
        <input type="text" name="img-path-music" id="img-path-music-input" />
        <label for="gif-path-music-input">Gif Path(option):</label>
        <input type="text" name="gif-path-music" id="gif-path-music-input" />
        <input type="submit" value="UpLoad Music" id="submit-music-btn" class="normal-btn" />
      </form>
    </div> -->
    <div class="music-box">
      <h2>Music Uploaded By You</h2>
      <table class="show-music-table-container">
        <thead>
          <tr>
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