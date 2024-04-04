const audio = new Audio();
var audioStatus = true;
var interact = false;
var onloadData,
  DataResponseSortByTag,
  currentPlaylist,
  libraryIDList = [];
var previousPlayLists,
  mixStatus = false;
var currentID = 0;
var FloatingUserAlbumsComponent = "";

// ---------------------------------------------- //
function audioControl(action) {
  switch (action) {
    case true:
      audio.play();
      $("#play-svg").html(
        `<path d="M8 5V19M16 5V19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`
      );
      interact = true;
      musicWave(interact, audio, 20);
      break;
    case false:
      audio.pause();
      $("#play-svg").html(`
        <path
        d="M16.6582 9.28638C18.098 10.1862 18.8178 10.6361 19.0647 11.2122C19.2803 11.7152 19.2803 12.2847 19.0647 12.7878C18.8178 13.3638 18.098 13.8137 16.6582 14.7136L9.896 18.94C8.29805 19.9387 7.49907 20.4381 6.83973 20.385C6.26501 20.3388 5.73818 20.0469 5.3944 19.584C5 19.053 5 18.1108 5 16.2264V7.77357C5 5.88919 5 4.94701 5.3944 4.41598C5.73818 3.9531 6.26501 3.66111 6.83973 3.6149C7.49907 3.5619 8.29805 4.06126 9.896 5.05998L16.6582 9.28638Z"
        stroke="white"
        stroke-width="2"
        stroke-linejoin="round"
        />
      `);
      break;
  }
}

function checkImg(imgUrl) {
  return imgUrl ? imgUrl : "assets/img/default.jpg";
}

function renderPlaylist(playlist, index) {
  playlist = playlist ? playlist : currentPlaylist;
  index = index ? index : currentID;

  var html = "";
  playlist.forEach((row, idx) => {
    if (idx === index + 1) html += `<p class="playlist-next-title">Next</p>`;
    html += `
        <div class="playlist-item ${
          idx == index ? "playlist-play" : ""
        }" onclick="clickToListen(${idx}, currentPlaylist)">
        <img
          src="${checkImg(row.imgPath)}"
          alt="no-img"
          class="playlists-img"
        />
        <div>
          <p class="playlist-music-name">${row.musicName}</p>
          <p>${row.author || "Unknown"}</p>
        </div>

        <div class='hidden-option-playlist-c'>
          <svg width="24px" height="24px" viewBox="0 0 23 23" fill="${
            libraryIDList.indexOf(row.id) == -1 ? "white" : "#FF00CC"
          }" onclick='addLibraryClick(${currentPlaylist[idx].id}, event)'>
              <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>  

          <svg width="22px" height="22px" viewBox="0 0 23 23" fill="none" onclick='deleteFromPlaylist(${JSON.stringify(
            row
          )}, event)'>
            <path d="M17 12C17 11.4477 16.5523 11 16 11H8C7.44772 11 7 11.4477 7 12C7 12.5523 7.44771 13 8 13H16C16.5523 13 17 12.5523 17 12Z" fill="white"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23ZM12 20.9932C7.03321 20.9932 3.00683 16.9668 3.00683 12C3.00683 7.03321 7.03321 3.00683 12 3.00683C16.9668 3.00683 20.9932 7.03321 20.9932 12C20.9932 16.9668 16.9668 20.9932 12 20.9932Z" fill="white"/>
          </svg>
        </div>
        </div>
      `;
    if (idx === index - 1)
      html += `<p class="playlist-next-title">Previous</p>`;
  });
  $("#playlist-container").html(html);
}

// -------------------------------------------------------- //
function addToMyAlbum(albumID) {
  var musicID = currentPlaylist[currentID].id;
  $(".pending-show-box").html("<div></div>");

  $.ajax({
    url: "server/server.php",
    type: "POST",
    data: {
      requestCode: 7,
      musicID,
      albumID,
    },
    success: (response) => {
      console.log(response);
      const res = JSON.parse(response);
      if (res.status) {
        $(".pending-show-box").html("<p style='color: white;'>Success!</p>");
      } else {
        $(".pending-show-box").html(
          `<p style='color: red;'>${res.message}</p>`
        );
      }
      // hideFloatLayer(false);
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
}

// -------------------------------------------------------- //
function clickToListen(id, playlists) {
  currentID = id ? id : 0;
  currentPlaylist = playlists ? playlists : currentPlaylist;

  audio.src = currentPlaylist[currentID].musicPath;

  renderPlaylist(currentPlaylist, currentID);
  audio.oncanplay = () => {
    // kiểm tra user đã nhấn vào button play lần nào hay chưa
    audioControl(interact);

    //
    const sec = Math.floor(audio.duration % 60);
    const minutes = Math.floor(audio.duration / 60);

    //
    $("#music-time-max").text(`${numpadS(minutes)}:${numpadS(sec)}`);
    $("#range-duration").attr("max", Math.round(audio.duration)); //gán max

    $("#play-music-info").html(`
        <img src="${checkImg(currentPlaylist[currentID].imgPath)}" alt="no-img">
        <div id="info">
            <p id="on-play-music-name">${
              currentPlaylist[currentID].musicName
            }</p>
            <p>${
              currentPlaylist[currentID].author
                ? currentPlaylist[currentID].author
                : "Unknown"
            }</p>
        </div>
        <div id="love-box-btn" onclick="addLibraryClick(${
          currentPlaylist[currentID].id
        })" title="add to your library">
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="${
              libraryIDList.indexOf(currentPlaylist[currentID].id) == -1
                ? "white"
                : "#FF00CC"
            }">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div id="add-to-my-album-box-btn" onclick="${
          _ == 1 ? "hideFloatLayer('', true)" : "callMessageBox()"
        }" title="add to your album">
          <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none">
            <g id="Edit / Add_Plus">
              <path id="Vector" d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
          </svg>
        </div>
    `);
  };
}

//
function callMessageBox() {
  hideFloatLayer("", true, {
    title: "Message",
    content: "You need to be logged in to perform this action",
    yes: "window.location.href = 'login.php'",
    no: "hideFloatLayer('', false)",
  });
}

// ----------------------------------------------------------------------- //
audio.onended = () => {
  if (currentPlaylist[currentID + 1]) {
    clickToListen(currentID + 1, currentPlaylist);
  } else {
    currentID = 0;
    clickToListen(currentID, currentPlaylist);
  }
};

// ----------------------------- load body ------------------------------- //
var prevLocationArray = [];
var myAlbum = false;

function toAlbumsPage(alIdx, myAlbum_) {
  myAlbum = myAlbum_ ? myAlbum_ : false;
  console.log(myAlbum_, " ", myAlbum);
  albumIndex = alIdx;
  loadBodyComponent("components/home_albums_component.php");
}

function renderAlbums(albumsList) {
  if ($(".mid-banner")) {
    var albumsHtmlComponent = "";
    albumsList.forEach((row, index) => {
      albumsHtmlComponent += `
      <div class="album-box" onclick="toAlbumsPage(${index}, false)">
        <img src="${row.albumImgPath}" alt="no-img">
        <p class="album-name">${row.albumName}</p>
        <p>${row.author}</p>
      </div>
      `;
    });
    $(".mid-banner").html(albumsHtmlComponent);
  }
}

function toPrevBody() {
  if (prevLocationArray[prevLocationArray.length - 2]) {
    loadBodyComponent(prevLocationArray[prevLocationArray.length - 2]);
    prevLocationArray.pop();
    console.log("to");
  }
  console.log(prevLocationArray);
}

function loadBodyComponent(page) {
  $.ajax({
    url: page,
    type: "GET",
    dataType: "html",
    success: (response) => {
      $(".body-web-show").html(response);
      if (prevLocationArray.indexOf(page) == -1) prevLocationArray.push(page);
    },
  });
}

// --------------------------------------------------------------- //
function loadUserAlbums(data) {
  var html = "";
  var html2 = "";
  if (data) {
    data.forEach((row, idx) => {
      html += `
      <div class="aside-left-album-item-box" onclick="toAlbumsPage(${idx}, true)">
        <p>${row.albumName}</p>
        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none">
          <path
            d="M5 12V6C5 5.44772 5.44772 5 6 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H12M8.11111 12H12M12 12V15.8889M12 12L5 19"
            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
      `;
      FloatingUserAlbumsComponent += `
      <div class="aside-left-album-item-box" onclick="addToMyAlbum(${row.id})">
        <p>${row.albumName}</p>
        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none">
          <path
            d="M5 12V6C5 5.44772 5.44772 5 6 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H12M8.11111 12H12M12 12V15.8889M12 12L5 19"
            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
      `;
    });
    if (html) $(".my-albums-show-container").html(html);
  }
}

// --------------------------------------------------------------- //
// onload
$("document").ready(() => {
  $.ajax({
    url: "server/server.php",
    type: "POST",
    data: {
      requestCode: 1,
    },
    success: (response) => {
      if (response == 0) {
        console.log("[request error] invalid request code");
        return;
      }
      onloadData = JSON.parse(response);
      libraryIDList = onloadData.library.map((row) => row.id);
      console.log(onloadData);

      // load body //
      loadBodyComponent("components/home_normal_component.php");

      currentPlaylist = onloadData.playlists;
      clickToListen(currentID, currentPlaylist);
      loadUserAlbums(onloadData.userAlbumsList);
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
  $("#volume-range").css("--value", 100);
  $("#volume-range").val(audio.volume * 100);
  $("#volume-percent-show").text(`${Math.round(audio.volume * 100)}%`);
});

function numpadS(num) {
  return num.toString().padStart(2, "0");
}

function audioVolume(value) {
  audio.volume = value / 100;
  $("#volume-percent-show").text(`${Math.round(audio.volume * 100)}%`);
}
function changeCurrentTimeP(value) {
  console.log(value);
  $("#music-time-current").text(
    `${numpadS(Math.floor(value / 60))}:${numpadS(Math.floor(value % 60))}`
  );
}

function onDurationChange(target) {
  audio.currentTime = target.value;
}

$("#range-duration").on("input", function () {
  var value = $(this).val();
  // Đặt giá trị của biến --value vào trong CSS của các phần tử input[type="range"]
  $("#range-duration").css("--value", (value / audio.duration) * 100);
});

$("#volume-range").on("input", function () {
  var value = $(this).val();
  $("#volume-range").css("--value", value);
});

audio.addEventListener("timeupdate", () => {
  const sec = Math.floor(audio.currentTime % 60);
  const minutes = Math.floor(audio.currentTime / 60);

  if (!isNaN(numpadS(minutes)) && !isNaN(numpadS(sec))) {
    $("#music-time-current").text(`${numpadS(minutes)}:${numpadS(sec)}`);
  }
  $("#range-duration").css(
    "--value",
    (audio.currentTime / audio.duration) * 100
  );
  $("#range-duration").val(audio.currentTime);
});

// ------------------------------------------------------------ //
function musicSkip(action) {
  if (action) {
    if (currentPlaylist[currentID + 1]) {
      clickToListen(currentID + 1, currentPlaylist);
    }
  } else {
    if (currentPlaylist[currentID - 1]) {
      clickToListen(currentID - 1, currentPlaylist);
    }
  }
}

function musicLoop() {
  if (audio.loop) {
    audio.loop = false;
    $(".ci-primary").attr("fill", "white");
  } else {
    audio.loop = true;
    $(".ci-primary").attr("fill", "#a84fc4");
  }
}

function audioMix() {
  if (!mixStatus) {
    if (!currentPlaylist) currentPlaylist = [...onloadData.playlists];
    previousPlayLists = [...currentPlaylist];

    for (var i = currentPlaylist.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var temp = currentPlaylist[i];
      currentPlaylist[i] = currentPlaylist[j];
      currentPlaylist[j] = temp;
    }
    $(".mix-arrow").attr("fill", "#a84fc4");

    mixStatus = true;
  } else {
    $(".mix-arrow").attr("fill", "white");
    currentPlaylist = [...previousPlayLists];
    mixStatus = false;
  }
  clickToListen(0, currentPlaylist);
}

// ------------------------------------------------------------ //
function userAvatarClick() {
  if ($(".nav-user-option").attr("id") == "nav-userAvatar-close") {
    $(".nav-user-option").attr("id", "nav-userAvatar-open");
    $(".user-nav-box").attr("id", "user-nav-box-open");
  } else {
    $(".nav-user-option").attr("id", "nav-userAvatar-close");
    $(".user-nav-box").attr("id", "");
  }
}

// ------------------------------------------------------------- //
function exist(music, callback) {
  // int element -> string element

  console.log(music);

  // check
  return currentPlaylist.some((child, index) => {
    if (JSON.stringify(child) === JSON.stringify(music)) {
      if (callback) callback(index);
      return true;
    }
    return false;
  });
}

function addToPlaylist(music) {
  if (
    !exist(music, (index) => {
      currentID = index;
      renderPlaylist();
      clickToListen(currentID, currentPlaylist);
      return true;
    })
  ) {
    currentPlaylist.splice(currentID + 1, 0, music);
    renderPlaylist();
  }
}

function deleteFromPlaylist(music, event) {
  // ngăn chặn việc gọi onclick của phần tử cha
  event.stopPropagation();

  // xóa
  exist(music, (index) => {
    currentPlaylist.splice(index, 1);
  });
  renderPlaylist(currentPlaylist, currentID);
}

// ------------------------------------------------------------- //
function searchMusic(searchString) {
  $.ajax({
    url: "server/server.php",
    type: "POST",
    data: {
      requestCode: 4,
      searchString,
    },
    success: (response) => {
      const searchMusicResult = JSON.parse(response);
      var searchHtmlElement = "";
      if (searchMusicResult) {
        searchMusicResult.forEach((row, index) => {
          searchHtmlElement += `
            <div class="playlist-item" style="${
              index == searchMusicResult.length - 1 ? "margin-bottom: 20px" : ""
            }">
              <img src="${checkImg(
                row.imgPath
              )}" alt="no-img" class="playlists-img" />
              <div>
                <p class="playlist-music-name">${row.musicName}</p>
                <p>${row.author}</p>
              </div>  
              <button class="add-playlist-al-btn">
                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" onclick='addToPlaylist(${JSON.stringify(
                  row
                )})'>
                  <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="white" stroke-width="1.5"/>
                  <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>  
              </button>
            </div>
          `;
        });
        $(".search-result-box").html(searchHtmlElement);
      }
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
}

// ------------------------------------------------------------- //
function createNewAlbumRequest() {
  var createNameAlbum = $("#create-name-album").val();

  $.ajax({
    url: "server/server.php",
    type: "POST",
    data: {
      requestCode: 6,
      createNameAlbum,
    },
    success: (response) => {
      console.log(response);
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
}

// ----------------------------------------------------- //
function hideFloatLayer(event, show, message) {
  if (!message) {
    $(".floating-div-container").html(`    
    <div class="floating-box">
      <h2>Choose Your Album</h2>
      <div class="floating-box-body">
        <!-- your list albums show here -->
      </div>
      <div class="pending-show-box">
      </div>
    </div>`);
  } else {
    $(".floating-div-container").html(`    
    <div class="floating-box">
      <h2>${message.title}</h2>
      <div class="floating-box-body">
        ${message.content}
      </div>
      <div class="pending-show-box">
        <button class="sl-left-eff-btn" onclick="${message.yes}"></button>
        <button class="sl-left-eff-btn no-btn-nt" onclick="${message.no}"></button>
      </div>
    </div>`);
  }

  if (FloatingUserAlbumsComponent)
    $(".floating-box-body").html(FloatingUserAlbumsComponent);
  if (
    event ? event.target.className == "floating-div-container" : true && !show
  ) {
    $(".floating-div-container").css("z-index", "-1");
  }
  if (show) {
    $(".floating-div-container").css("z-index", "12");
  }
}
