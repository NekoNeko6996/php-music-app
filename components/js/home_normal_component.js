var albumIndex = null;
function selectTag(event, tag) {
  document.querySelectorAll(".category-tag").forEach((element) => {
    element.classList.remove("tag-selected");
  });
  event.target.classList.add("tag-selected");
  if (tag) {
    $.ajax({
      url: "server/server.php",
      type: "POST",
      data: {
        requestCode: 2,
        data: tag,
      },
      success: (response) => {
        DataResponseSortByTag = JSON.parse(response);
        // console.log(DataResponseSortByTag);
        if (DataResponseSortByTag.length > 0) {
          currentMusicTagLoaded = DataResponseSortByTag;
          loadMusicPage(1, 30);
        } else {
          $(".category-show-item").html(`
              <h1 class="nothing-text">Hmm... Nothing to see here</h1>
              <h3 class="nothing-text-h3">You can add songs to this list by clicking on the heart</h3>
            `);
        }
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  }
}

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

function sortByTagRenderItem(array) {
  var musicByTagComponent = "";
  array.forEach((row, index) => {
    musicByTagComponent += loadMusicItemByTag(
      row.imgPath,
      row.musicName,
      row.author,
      libraryIDList.indexOf(row.id) == -1 ? "white" : "#FF00CC",
      row.id,
      row
    );
  });
  $(".category-show-item").html(musicByTagComponent);
}

// ------------------------------------------------------- //
var newMusicComponent = (top3MusicComponent = "");

onloadData.newMusic.forEach((row, index) => {
  newMusicComponent += `
                <div class="music-item-box" onclick="clickToListen(${index}, onloadData.newMusic)">
                  <img src="${checkImg(row.imgPath)}" alt="no-img"> 
                  <div class="info">
                    <p class="music-name">${row.musicName}</p>  
                    <p class="music-author">${row.author || "Unknown"}</p>
                    <p class="time-upload">${dateUploadCalculator(
                      row.timeUpload
                    )}</p>
                  </div>
                </div>`;
});

onloadData.top3Music.forEach((row, index) => {
  top3MusicComponent += `
                <div class='top-music-item ${
                  index == 0 ? "scale" : ""
                }' onclick="clickToListen(${index}, onloadData.top3Music)">
                  <img src="${checkImg(row.imgPath)}" alt="no-img" />
                  <div class="info">
                    <p class="music-name">${row.musicName}</p>
                    <p class="music-author">${row.author || "Unknown"}</p>
                    <p class="time-upload">${dateUploadCalculator(
                      row.timeUpload
                    )}</p>
                  </div>
                  <div class="top-info-box">
                    <p class="top-text">TOP ${index + 1}</p>
                    <p>LISTENS ${thousandConvert(row.listens)}</p>
                  </div>
                </div>
              `;
});

// ------------------------------------------------------- //
var musicPage = 1;
var currentMusicTagLoaded = [];
function loadMusicPage(page, limit, array_) {
  const Array = array_ ? array_ : currentMusicTagLoaded;
  let html = "";
  if (page) {
    if (page >= 1 && page <= Math.ceil(Array.length / limit)) {
      musicPage = page;
    }
  }

  if (Array.length > limit) {
    var newDataArray = Array.slice((musicPage - 1) * limit, musicPage * limit);
    sortByTagRenderItem(newDataArray);
  } else {
    sortByTagRenderItem(Array);
  }

  for (let count = 1; count <= Math.ceil(Array.length / limit); count++) {
    html += `<div class="pagination-child-button" onclick="loadMusicPage(${count}, 30)">${count}</div>`;
  }
  $(".pagination-child-button-show").html(html);
  $(".pagination-child-button")
    .eq(musicPage - 1)
    .css("background-color", "#a84fc4");
}

// -------------------------------------- //
$(document).ready(() => {
  loadMusicPage(1, 30, onloadData.musicByTag); // js/home.js line 527
  currentMusicTagLoaded = [...onloadData.musicByTag];
  // sortByTagRenderItem(onloadData.musicByTag);
  renderAlbums(onloadData.albumsLoad);
  $(".category-tag").eq(0).addClass("tag-selected");

  if (!_) {
    $(".join-with-us-container").html(`
      <h2>New Member?</h2>
      <button type="button" title="register" onclick="window.location.href='signup.php'" class="join-us-btn"></button>
    `);
  }
});

// -------------------------------------- //
$(".top-1-show").html(
  `<img src="${checkImg(onloadData.top3Music[0].imgPath)}" alt="no-img">`
);
$(".top-table").html(top3MusicComponent);
$(".new-music-box").html(newMusicComponent);
