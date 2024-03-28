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
if (isset($_SESSION["user"]) && isset($_SESSION["permissionID"]) && $_SESSION["permissionID"] != 3 && $_SESSION["permissionID"] != -1) {
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
    <div class="add-new-music">
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
    </div>
    <div class="music-update-box">
      <h2>Update Music</h2>
      <form action="" method="POST" id="update-music-form-data">
        <table>
          <!-- <tr>
            <td><label for="update-music-id">Input ID</label></td>
            <td></td>
          </tr>
          <tr>
            <td>
              <input type="number" name="musicId" id="update-music-id" class="update-input" />
            </td>
            <td>
              <button class="normal-btn f-left no-margin" onclick="loadMusicData(event)">
                Search Music
              </button>
            </td>
          </tr>
          <tr>
            <td>
              <label for="update-music-name">Music Name</label>
              <input type="text" name="musicName" id="update-music-name" class="update-input" />
            </td>
            <td>
              <label for="update-music-path">Music Path</label>
              <input type="text" name="musicPath" id="update-music-path" class="update-input" />
            </td>
          </tr>
          <tr>
            <td>
              <label for="update-music-author">Music Author</label>
              <input type="text" name="musicAuthor" id="update-music-author" class="update-input" />
            </td>
            <td>
              <label for="update-img-path">Music Image</label>
              <input type="text" name="imgPath" id="update-img-path" class="update-input" />
            </td>
          </tr>
          <tr>
            <td>
              <label for="update-gif-path">Music Gif</label>
              <input type="text" name="update-gif-path" id="update-gif-path" class="update-input" />
            </td>
            <td>
              <label for="update-music-tag">Music Tag</label>
              <input type="text" name="update-music-tag" id="update-music-tag" class="update-input" />
            </td>
          </tr>
          <tr>
            <td></td>
            <td>
              <button class="normal-btn" onclick="updateMusicRequest(event)">
                Update Music
              </button>
              <button class="normal-btn delete-btn">Delete Music</button>
            </td>
          </tr> -->

          

        </table>
      </form>
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