<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/adminpage.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/adminPage.js" defer></script>
    <title>ADMIN DASHBOARD</title>
  </head>

  <body>
    <nav class="admin-page-nav"></nav>
    <div class="music-edit-container">
      <div class="add-new-music">
        <form
          action=""
          id="uploadMusic-form"
          method="post"
          onsubmit="uploadMusic(event)"
        >
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
          <input type="submit" value="UpLoad Music" id="submit-music-btn" />
        </form>
      </div>
      <div class="music-update-box">
        <h2>Update Music</h2>
        <form action="" method="POST" id="update-music-form-data">
          <table>
            <tr>
              <td>
                <label for="update-music-id">Input ID</label>
                <input
                  type="number"
                  name="musicId"
                  id="update-music-id"
                  class="update-input"
                />
              </td>
              <td>
                <button class="update-music-btn" onclick="loadMusicData(event)">
                  Search Music
                </button>
              </td>
            </tr>
            <tr>
              <td>
                <label for="update-music-name">Music Name</label>
                <input
                  type="text"
                  name="musicName"
                  id="update-music-name"
                  class="update-input"
                />
              </td>
              <td>
                <label for="update-music-path">Music Path</label>
                <input
                  type="text"
                  name="musicPath"
                  id="update-music-path"
                  class="update-input"
                />
              </td>
            </tr>
            <tr>
              <td>
                <label for="update-music-author">Music Author</label>
                <input
                  type="text"
                  name="musicAuthor"
                  id="update-music-author"
                  class="update-input"
                />
              </td>
              <td>
                <label for="update-img-path">Music Image</label>
                <input
                  type="text"
                  name="imgPath"
                  id="update-img-path"
                  class="update-input"
                />
              </td>
            </tr>
            <tr>
              <td>
                <label for="update-gif-path">Music Gif</label>
                <input
                  type="text"
                  name="update-gif-path"
                  id="update-gif-path"
                  class="update-input"
                />
              </td>
              <td>
                <label for="update-music-tag">Music Tag</label>
                <input
                  type="text"
                  name="update-music-tag"
                  id="update-music-tag"
                  class="update-input"
                />
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <button class="update-music-btn" onclick="updateMusicRequest(event)">
                  Update Music
                </button>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
    <div class="user-display">
      <table id="user-display-table">
        <!-- user display -->
      </table>
    </div>
  </body>
</html>
