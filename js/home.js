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
  return imgUrl ? imgUrl : "music/img/default.jpg";
}

function renderPlaylist(playlist, index) {
  var html = "";
  playlist.forEach((row, idx) => {
    if (idx === index + 1) html += `<p class="playlist-next-title">Next</p>`;
    html += `
        <div class="playlist-item ${
          idx == index ? "playlist-play" : ""
        }" onclick="clickToListen(${idx}, currentPlaylist)">
        <img
          src="${checkImg(row[4])}"
          alt="no-img"
          class="playlists-img"
        />
        <div>
          <p class="playlist-music-name">${row[1]}</p>
          <p>${row[3] || "Unknown"}</p>
        </div>
        </div>
      `;
    if (idx === index - 1)
      html += `<p class="playlist-next-title">Previous</p>`;
  });
  $("#playlist-container").html(html);
}

function clickToListen(id, playlists) {
  audio.src = playlists[id][2];
  currentID = id;
  currentPlaylist = [...playlists];
  console.log(playlists);
  renderPlaylist(playlists, id);
  audio.oncanplay = () => {
    // kiểm tra user đã nhấn vào button play lần nào hay chưa
    audioControl(interact);

    //
    const sec = Math.round(audio.duration % 60);
    const minutes = Math.round(audio.duration / 60);

    //
    $("#music-time-max").text(`${numpadS(minutes)}:${numpadS(sec)}`);
    $("#music-time-current").text(`00:00`);
    $("#range-duration").attr("max", Math.round(audio.duration)); //gán max

    $("#play-music-info").html(`
        <img src="${checkImg(playlists[id][4])}" alt="no-img">
        <div id="info">
            <p id="on-play-music-name">${playlists[id][1]}</p>
            <p>${playlists[id][3]}</p>
        </div>
        <div id="love-box-btn" onclick="addLibraryClick(${playlists[id][0]})">
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="${
              libraryIDList.indexOf(playlists[id][0]) == -1
                ? "white"
                : "#FF00CC"
            }">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    `);
  };
}

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
var albumID = null;

function toPrevBody() {
  if(prevLocationArray[prevLocationArray.length - 2]) {
    loadBodyComponent(prevLocationArray[prevLocationArray.length - 2])
    prevLocationArray.pop();
    console.log("to")
  }
  console.log(prevLocationArray)
}

function toAlbumsPage(alID) {
  albumID = alID;
  loadBodyComponent("components/home_albums_component.html");
}


function loadBodyComponent(page) {
  $.ajax({
    url: page,
    type: "GET",
    dataType: "html",
    success: (response) => {
      $(".body-web-show").html(response);
      prevLocationArray.push(page);
    }
  })
}


// --------------------------------------------------------------- //
function renderAlbums(albumsList) {
  if ($(".mid-banner")) {
    var albumsHtmlComponent = "";
    albumsList.forEach((row, index) => {
      albumsHtmlComponent += `
      <div class="album-box" onclick="toAlbumsPage(${row[0]})">
        <img src="${row[4]}" alt="no-img">
        <p class="album-name">${row[1]}</p>
        <p>${row[2]}</p>
      </div>
      `;
    });
    $(".mid-banner").html(albumsHtmlComponent);
  }
}

// onload
$("document").ready(() => {
  $.ajax({
    url: "server/server.php",
    type: "POST",
    data: {
      requestCode: 1,
      userEmail,
    },
    success: (response) => {
      if (response == 0) {
        console.log("[request error] invalid request code");
        return;
      }
      onloadData = JSON.parse(response.replace("<!-- Server -->", ""));
      libraryIDList = onloadData.library.map((row) => row[0].toString());
      console.log(onloadData);

      // load body //
      loadBodyComponent("components/home_normal_component.html");


      currentPlaylist = onloadData.playlists;
      clickToListen(currentID, currentPlaylist);
    },
  });
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

function onDurationChange(value) {
  audio.currentTime = value;
}

audio.addEventListener("timeupdate", () => {
  const sec = Math.round(audio.currentTime % 60);
  const minutes = Math.round(audio.currentTime / 60);

  if (!isNaN(numpadS(minutes)) && !isNaN(numpadS(sec))) {
    $("#music-time-current").text(`${numpadS(minutes)}:${numpadS(sec)}`);
  }

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
function logoutF() {
  window.location.href = "server/logout.php";
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
      const searchMusicResult = JSON.parse(
        response.replace("<!-- Server -->", "")
      );
      var searchHtmlElement = "";
      if (searchMusicResult) {
        searchMusicResult.forEach((row, index) => {
          searchHtmlElement += `
            <div class="playlist-item" onclick="" style="${
              index == searchMusicResult.length - 1 ? "margin-bottom: 20px" : ""
            }">
              <img src="${checkImg(
                row[4]
              )}" alt="no-img" class="playlists-img" />
              <div>
                <p class="playlist-music-name">${row[1]}</p>
                <p>${row[3]}</p>
              </div>  
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
function onclickAlbums(albumID) {}
