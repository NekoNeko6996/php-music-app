const audio = new Audio();
var audioStatus = true;
var interact = false;
var onloadData, DataResponseSortByTag, currentPlaylist;
var currentID = 0;

// ---------------------------------------------- //
function dateUploadCalculator(date) {
  const result = Math.floor(
    (new Date() - new Date(date)) / (1000 * 60 * 60 * 24)
  );
  if (result == 0) return "Today";
  if (result == 1) return "Yesterday";
  return `${result} Days Before`;
}

function thousandConvert(number) {
  if (number < 1000) return number;
  if (number > 999) {
    return `${(number / 1000).toFixed(1)}k`;
  }
}

function audioControl(action) {
  switch (action) {
    case true:
      audio.play();
      $("#play-svg").html(
        `<path d="M8 5V19M16 5V19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>`
      );
      interact = true;
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
        <div id="love-box-btn">
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="${"white"}" xmlns="http://www.w3.org/2000/svg">
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


function sortByTagRenderItem(array, nameArray) {
  var musicByTagComponent = "";
  array.forEach((row, index) => {
    musicByTagComponent += loadMusicItemByTag(row[4], row[1], row[3], index, nameArray);
  });
  $(".category-show-item").html(musicByTagComponent);
}


// onload
$("document").ready(() => {
  $.ajax({
    url: "server/server.php",
    type: "POST",
    data: {
      requestCode: 1,
      data: "",
    },
    success: (response) => {
      if (response == 0) {
        console.log("[request error] invalid request code");
        return;
      }
      onloadData = JSON.parse(response.replace("<!-- Server -->", ""));
      console.log(onloadData);
      var newMusicComponent = (top3MusicComponent = "");

      // --------------------------------------------------------------------------- //

      onloadData.newMusic.forEach((row, index) => {
        newMusicComponent += `
          <div class="music-item-box" onclick="clickToListen(${index}, onloadData.newMusic)">
            <img src="${checkImg(row[4])}" alt="no-img"> 
            <div class="info">
              <p class="music-name">${row[1]}</p>
              <p class="music-author">${row[3] || "Unknown"}</p>
              <p class="time-upload">${dateUploadCalculator(row[5])}</p>
            </div>
          </div>`;
      });

      // --------------------------------------------------------------------------- //

      onloadData.top3Music.forEach((row, index) => {
        top3MusicComponent += `
          <div class='top-music-item ${
            index == 0 ? "scale" : ""
          }' onclick="clickToListen(${index}, onloadData.top3Music)">
            <img src="${checkImg(row[4])}" alt="no-img" />
            <div class="info">
              <p class="music-name">${row[1]}</p>
              <p class="music-author">${row[3] || "Unknown"}</p>
              <p class="time-upload">${dateUploadCalculator(row[5])}</p>
            </div>
            <div class="top-info-box">
              <p class="top-text">TOP ${index + 1}</p>
              <p>LISTENS ${thousandConvert(row[6])}</p>
            </div>
          </div>
        `;
      });

      // --------------------------------------------------------------------------- //
      sortByTagRenderItem(onloadData.musicByTag, "onloadData.musicByTag");
      // --------------------------------------------------------------------------- //

      $(".top-1-show").html(
        `<img src="${
          // onloadData.top3Music[0][7]
          //   ? onloadData.top3Music[0][7]
          //   : checkImg(onloadData.top3Music[0][4])

          checkImg(onloadData.top3Music[0][4])
        }" alt="no-img">`
      );
      $(".top-table").html(top3MusicComponent);
      $(".new-music-box").html(newMusicComponent);
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
  console.log(value);
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

// ----------------------------------------- //
function sortByTag(tag) {
  if (tag) {
    $.ajax({
      url: "server/server.php",
      type: "POST",
      data: {
        requestCode: 2,
        data: tag,
      },
      success: (response) => {
        DataResponseSortByTag = JSON.parse(response.replace("<!-- Server -->", ""));
        if(DataResponseSortByTag) {
          sortByTagRenderItem(DataResponseSortByTag, "DataResponseSortByTag");
        }
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  }
}
