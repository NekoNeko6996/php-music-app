<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/home.css" />
  <link rel="stylesheet" href="css/musicWave.css">
  <link rel="stylesheet" href="css/albumsPage.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="js/largeItemHtml.js"></script>
  <script src="js/musicWave.js" defer></script>
  <script src="js/home.js" defer></script>
  <title>Home</title>
</head>


<?php
session_start();
if (isset ($_SESSION["user"]) && isset ($_SESSION["username"])) {
  $user = $_SESSION["user"];
  $username = $_SESSION["username"];
  $permission = $_SESSION["permissionID"];
} else {
  $username = "Guest";
  $permission = -1;
}
?>

<!-- Client -->

<body>
  <aside class="aside-left">
    <div id="WaveContainer"></div>
  </aside>
  <section class="mid-body">
    <nav class="mid-nav">
      <!-- prev btn -->
      <button type="button" onclick="toPrevBody()" class="prev-body-btn">
        <svg width="27px" height="24px" viewBox="0 0 1024 1024">
          <path fill="white" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z" />
          <path fill="white"
            d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z" />
        </svg>
      </button>

      <!-- search input -->
      <div class="search-container">
        <form action="">
          <input type="text" name="search-music" id="search-music-input" placeholder="Search..."
            oninput="searchMusic(event.target.value)" onfocus="searchInputFocus(true)"
            onblur="searchInputFocus(false)" />
          <button type="submit" id="search-btn-nav">
            <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none">
              <path
                d="M17.5556 3C19.4579 3 21 4.54213 21 6.44444V17.5556C21 19.4579 19.4579 21 17.5556 21H6.44444C4.54213 21 3 19.4579 3 17.5556V6.44444C3 4.54213 4.54213 3 6.44444 3H17.5556Z"
                stroke="white" stroke-width="2" />
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M11.5067 7.01392C9.02527 7.01392 7.01367 9.02551 7.01367 11.5069C7.01367 13.9884 9.02527 16 11.5067 16C12.3853 16 13.205 15.7478 13.8973 15.3119L15.1658 16.5803C15.5563 16.9709 16.1895 16.9709 16.58 16.5803C16.9705 16.1898 16.9705 15.5566 16.58 15.1661L15.3116 13.8977C15.7475 13.2053 15.9997 12.3856 15.9997 11.5069C15.9997 9.02551 13.9881 7.01392 11.5067 7.01392ZM9.01367 11.5069C9.01367 10.1301 10.1298 9.01392 11.5067 9.01392C12.8836 9.01392 13.9997 10.1301 13.9997 11.5069C13.9997 12.8838 12.8836 14 11.5067 14C10.1298 14 9.01367 12.8838 9.01367 11.5069Z"
                fill="white" />
            </svg>
          </button>
        </form>
        <div class="search-result-box">
          <!-- search result here -->
        </div>
      </div>
      <div class="user-nav-box">
        <span onclick="userAvatarClick(event.target)">
          <p>
            <?php
            echo $username ?>
          </p>
          <img src="music/img/default.jpg" alt="no-img" class="user-avatar">
        </span>
        <div class="nav-user-option" id="nav-userAvatar-close">
          <?php if ($permission != -1)
            echo '<p onclick="logoutF()">Logout</p>';
          else
            echo '<p onclick="window.location.href=\'login.php\'">Login</p><p onclick="window.location.href=\'signup.php\'">Sign Up</p>' ?>
            <?php
          if (isset ($permission) && $permission != 3 && $permission != -1)
            echo '<p onclick="window.location.href =\'admin.php\'">Admin Page</p>';
          ?>
        </div>
      </div>
    </nav>

    <!-- --------------- body ------------------- -->
    <span class="body-web-show">
      <!-- body web show here -->
    </span>
    <!-- ---------------------------------------- -->

    <footer></footer>
  </section>
  <aside class="aside-right">
    <p class="playlists-title">Playlists</p>
    <div id="playlist-container"></div>
  </aside>

  <div class="play-zone">
    <div id="play-music-info"></div>
    <div class="play-zone-btn-box">
      <button class="audio-control-btn" onclick="audioMix()">
        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="white">
          <path
            d="M2 16.25C1.58579 16.25 1.25 16.5858 1.25 17C1.25 17.4142 1.58579 17.75 2 17.75V16.25ZM10.7478 14.087L10.1047 13.7011L10.7478 14.087ZM13.2522 9.91303L13.8953 10.2989L13.2522 9.91303ZM22 7L22.5303 7.53033C22.8232 7.23744 22.8232 6.76256 22.5303 6.46967L22 7ZM19.4697 8.46967C19.1768 8.76256 19.1768 9.23744 19.4697 9.53033C19.7626 9.82322 20.2374 9.82322 20.5303 9.53033L19.4697 8.46967ZM20.5303 4.46967C20.2374 4.17678 19.7626 4.17678 19.4697 4.46967C19.1768 4.76256 19.1768 5.23744 19.4697 5.53033L20.5303 4.46967ZM15.2205 7.3894L14.851 6.73675V6.73675L15.2205 7.3894ZM2 17.75H5.60286V16.25H2V17.75ZM11.3909 14.4728L13.8953 10.2989L12.6091 9.52716L10.1047 13.7011L11.3909 14.4728ZM18.3971 7.75H22V6.25H18.3971V7.75ZM21.4697 6.46967L19.4697 8.46967L20.5303 9.53033L22.5303 7.53033L21.4697 6.46967ZM22.5303 6.46967L20.5303 4.46967L19.4697 5.53033L21.4697 7.53033L22.5303 6.46967ZM13.8953 10.2989C14.3295 9.57518 14.6286 9.07834 14.9013 8.70996C15.1644 8.35464 15.3692 8.16707 15.59 8.04205L14.851 6.73675C14.384 7.00113 14.0315 7.36397 13.6958 7.8174C13.3697 8.25778 13.0285 8.82806 12.6091 9.52716L13.8953 10.2989ZM18.3971 6.25C17.5819 6.25 16.9173 6.24918 16.3719 6.30219C15.8104 6.35677 15.3179 6.47237 14.851 6.73675L15.59 8.04205C15.8108 7.91703 16.077 7.83793 16.517 7.79516C16.9733 7.75082 17.5531 7.75 18.3971 7.75V6.25ZM5.60286 17.75C6.41814 17.75 7.0827 17.7508 7.62807 17.6978C8.18961 17.6432 8.6821 17.5276 9.14905 17.2632L8.41 15.9579C8.18919 16.083 7.92299 16.1621 7.48296 16.2048C7.02675 16.2492 6.44685 16.25 5.60286 16.25V17.75ZM10.1047 13.7011C9.67046 14.4248 9.37141 14.9217 9.09867 15.29C8.8356 15.6454 8.63081 15.8329 8.41 15.9579L9.14905 17.2632C9.616 16.9989 9.96851 16.636 10.3042 16.1826C10.6303 15.7422 10.9715 15.1719 11.3909 14.4728L10.1047 13.7011Z"
            fill="white" class="mix-arrow" />
          <path opacity="0.5"
            d="M2 7.75C1.58579 7.75 1.25 7.41421 1.25 7C1.25 6.58579 1.58579 6.25 2 6.25V7.75ZM10.7478 9.91303L10.1047 10.2989L10.7478 9.91303ZM13.2522 14.087L13.8953 13.7011L13.2522 14.087ZM22 17L22.5303 16.4697C22.8232 16.7626 22.8232 17.2374 22.5303 17.5303L22 17ZM19.4697 15.5303C19.1768 15.2374 19.1768 14.7626 19.4697 14.4697C19.7626 14.1768 20.2374 14.1768 20.5303 14.4697L19.4697 15.5303ZM20.5303 19.5303C20.2374 19.8232 19.7626 19.8232 19.4697 19.5303C19.1768 19.2374 19.1768 18.7626 19.4697 18.4697L20.5303 19.5303ZM15.2205 16.6106L14.851 17.2632V17.2632L15.2205 16.6106ZM2 6.25H5.60286V7.75H2V6.25ZM11.3909 9.52715L13.8953 13.7011L12.6091 14.4728L10.1047 10.2989L11.3909 9.52715ZM18.3971 16.25H22V17.75H18.3971V16.25ZM21.4697 17.5303L19.4697 15.5303L20.5303 14.4697L22.5303 16.4697L21.4697 17.5303ZM22.5303 17.5303L20.5303 19.5303L19.4697 18.4697L21.4697 16.4697L22.5303 17.5303ZM13.8953 13.7011C14.3295 14.4248 14.6286 14.9217 14.9013 15.29C15.1644 15.6454 15.3692 15.8329 15.59 15.9579L14.851 17.2632C14.384 16.9989 14.0315 16.636 13.6958 16.1826C13.3697 15.7422 13.0285 15.1719 12.6091 14.4728L13.8953 13.7011ZM18.3971 17.75C17.5819 17.75 16.9173 17.7508 16.3719 17.6978C15.8104 17.6432 15.3179 17.5276 14.851 17.2632L15.59 15.9579C15.8108 16.083 16.077 16.1621 16.517 16.2048C16.9733 16.2492 17.5531 16.25 18.3971 16.25V17.75ZM5.60286 6.25C6.41814 6.25 7.0827 6.24918 7.62807 6.30219C8.18961 6.35677 8.6821 6.47237 9.14905 6.73675L8.41 8.04205C8.18919 7.91703 7.92299 7.83793 7.48296 7.79516C7.02675 7.75082 6.44685 7.75 5.60286 7.75V6.25ZM10.1047 10.2989C9.67046 9.57518 9.37141 9.07834 9.09867 8.70996C8.8356 8.35464 8.63081 8.16707 8.41 8.04205L9.14905 6.73675C9.616 7.00113 9.96851 7.36397 10.3042 7.8174C10.6303 8.25778 10.9715 8.82806 11.3909 9.52715L10.1047 10.2989Z"
            fill="white" class="mix-arrow" />
        </svg>
      </button>
      <button class="audio-control-btn" onclick="musicSkip(false)">
        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none">
          <path
            d="M20.24 7.22005V16.7901C20.24 18.7501 18.11 19.98 16.41 19L12.26 16.61L8.10996 14.21C6.40996 13.23 6.40996 10.78 8.10996 9.80004L12.26 7.40004L16.41 5.01006C18.11 4.03006 20.24 5.25005 20.24 7.22005Z"
            stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="white" />
          <path d="M3.76001 18.1801V5.82007" stroke="white" stroke-width="1.5" stroke-linecap="round"
            stroke-linejoin="round" fill="white" />
        </svg>
      </button>
      <button class="audio-control-btn play-btn" id="playBtn" onclick="audioControl(audio.paused)">
        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" id="play-svg">
          <path
            d="M16.6582 9.28638C18.098 10.1862 18.8178 10.6361 19.0647 11.2122C19.2803 11.7152 19.2803 12.2847 19.0647 12.7878C18.8178 13.3638 18.098 13.8137 16.6582 14.7136L9.896 18.94C8.29805 19.9387 7.49907 20.4381 6.83973 20.385C6.26501 20.3388 5.73818 20.0469 5.3944 19.584C5 19.053 5 18.1108 5 16.2264V7.77357C5 5.88919 5 4.94701 5.3944 4.41598C5.73818 3.9531 6.26501 3.66111 6.83973 3.6149C7.49907 3.5619 8.29805 4.06126 9.896 5.05998L16.6582 9.28638Z"
            stroke="white" stroke-width="2" stroke-linejoin="round" />
        </svg>
      </button>
      <button class="audio-control-btn" onclick="musicSkip(true)">
        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="white">
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M8.715 6.36694L14.405 10.6669C14.7769 10.9319 14.9977 11.3603 14.9977 11.8169C14.9977 12.2736 14.7769 12.702 14.405 12.9669L8.715 17.6669C8.23425 18.0513 7.58151 18.1412 7.01475 17.9011C6.44799 17.6611 6.05842 17.1297 6 16.5169V7.51694C6.05842 6.90422 6.44799 6.37281 7.01475 6.13275C7.58151 5.89269 8.23425 5.9826 8.715 6.36694Z"
            stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="white" />
          <path d="M18 6.01697V18.017" stroke="white" stroke-width="1.5" stroke-linecap="round" />
        </svg>
      </button>
      <button class="audio-control-btn" onclick="musicLoop()">
        <svg width="100%" height="100%" viewBox="0 0 556 556" fill="white" id="audioLoop-svg-active"
          class="audioLoop-svg">
          <path
            d="M415.313,358.7c36.453-36.452,55.906-85.231,54.779-137.353-1.112-51.375-21.964-99.908-58.715-136.66L388.75,107.314A166.816,166.816,0,0,1,438.1,222.039c.937,43.313-15.191,83.81-45.463,114.083l-48.617,49.051.044-89.165-32-.016L311.992,440H456.063V408H366.449Z"
            class="ci-primary" fill="white" />
          <path
            d="M47.937,112h89.614L88.687,161.3c-36.453,36.451-55.906,85.231-54.779,137.352a198.676,198.676,0,0,0,58.715,136.66l22.627-22.627A166.818,166.818,0,0,1,65.9,297.962c-.937-43.314,15.191-83.811,45.463-114.083l48.617-49.051-.044,89.165,32,.015L192.008,80H47.937Z"
            class="ci-primary" fill="white" />
        </svg>
      </button>
    </div>
    <div class="play-zone-btn-box">
      <p id="music-time-current" class="music-time-p">00:00</p>
      <input type="range" name="music" id="range-duration" min="0" max="300" value="0"
        onchange="onDurationChange(event.target)"/>
      <p id="music-time-max" class="music-time-p">00:00</p>
    </div>
    <div class="music-volume-control">
      <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none">
        <path
          d="M16.0004 9.00009C16.6281 9.83575 17 10.8745 17 12.0001C17 13.1257 16.6281 14.1644 16.0004 15.0001M18 5.29177C19.8412 6.93973 21 9.33459 21 12.0001C21 14.6656 19.8412 17.0604 18 18.7084M4.6 9.00009H5.5012C6.05213 9.00009 6.32759 9.00009 6.58285 8.93141C6.80903 8.87056 7.02275 8.77046 7.21429 8.63566C7.43047 8.48353 7.60681 8.27191 7.95951 7.84868L10.5854 4.69758C11.0211 4.17476 11.2389 3.91335 11.4292 3.88614C11.594 3.86258 11.7597 3.92258 11.8712 4.04617C12 4.18889 12 4.52917 12 5.20973V18.7904C12 19.471 12 19.8113 11.8712 19.954C11.7597 20.0776 11.594 20.1376 11.4292 20.114C11.239 20.0868 11.0211 19.8254 10.5854 19.3026L7.95951 16.1515C7.60681 15.7283 7.43047 15.5166 7.21429 15.3645C7.02275 15.2297 6.80903 15.1296 6.58285 15.0688C6.32759 15.0001 6.05213 15.0001 5.5012 15.0001H4.6C4.03995 15.0001 3.75992 15.0001 3.54601 14.8911C3.35785 14.7952 3.20487 14.6422 3.10899 14.4541C3 14.2402 3 13.9601 3 13.4001V10.6001C3 10.04 3 9.76001 3.10899 9.54609C3.20487 9.35793 3.35785 9.20495 3.54601 9.10908C3.75992 9.00009 4.03995 9.00009 4.6 9.00009Z"
          stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <input type="range" name="volume-range" id="volume-range" oninput="audioVolume(event.target.value)" min="0"
        max="100" value="100" />
      <p id="volume-percent-show"></p>
    </div>
  </div>

  <script>
    const userEmail = "<?php echo isset ($_SESSION['user']) ? $_SESSION['user'] : '' ?>";
    function addLibraryClick(musicID, event) {
      if(event) event.stopPropagation();

      if (userEmail != '') {
        $.ajax({
          url: "server/server.php",
          type: "POST",
          data: {
            requestCode: 3,
            musicID,
            userEmail
          },
          success: (response) => {
            console.log(response);
            renderPlaylist();
          },
          error: (status, error) => {
            console.error(status, error);
          },
        })
      } else window.location.href = "login.php";
    }

    function searchInputFocus(focus) {
      if (focus) $(".search-result-box").css("display", "block");
      else $(".search-result-box").css("display", "none");
    }
  </script>
</body>

</html>