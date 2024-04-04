<body>
  <h3 class="albums-title">Albums</h3>
  <div class="mid-banner">
    <!-- albums show here -->
  </div>

  <h3 class="new-music-title" style="color: var(--text-color)">New Release</h3>
  <div class="new-music-box"></div>

  <div class="top-music-box">
    <div class="top-table"></div>
    <div class="top-1-show"></div>
  </div>

  <div class="category-box">
    <nav>
      <div class="category-tag" onclick="selectTag(event, 'vietnam')">
        VietNam
      </div>
      <div class="category-tag" onclick="selectTag(event, 'foreign')">
        Foreign
      </div>
      <div class="category-tag" onclick="selectTag(event, 'gamethemesong')">
        Game Music Theme
      </div>
      <div class="category-tag" onclick="selectTag(event, 'random')">
        Random
      </div>
      <div class="category-tag" onclick="selectTag(event, 'library')">
        Your Library
      </div>
    </nav>
    <section class="category-show-item">
      <!-- items show here -->
    </section>
  </div>

  <script>
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
            console.log(DataResponseSortByTag)
            if (DataResponseSortByTag.length > 0) {
              sortByTagRenderItem(
                DataResponseSortByTag
              );
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
          libraryIDList.indexOf(row.id) == -1 ? "none" : "#ba63d4",
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
              <p class="time-upload">${dateUploadCalculator(row.timeUpload)}</p>
            </div>
          </div>`;
    });

    onloadData.top3Music.forEach((row, index) => {
      top3MusicComponent += `
          <div class='top-music-item ${index == 0 ? "scale" : ""
        }' onclick="clickToListen(${index}, onloadData.top3Music)">
            <img src="${checkImg(row.imgPath)}" alt="no-img" />
            <div class="info">
              <p class="music-name">${row.musicName}</p>
              <p class="music-author">${row.author || "Unknown"}</p>
              <p class="time-upload">${dateUploadCalculator(row.timeUpload)}</p>
            </div>
            <div class="top-info-box">
              <p class="top-text">TOP ${index + 1}</p>
              <p>LISTENS ${thousandConvert(row.listens)}</p>
            </div>
          </div>
        `;
    });

    // -------------------------------------- //

    sortByTagRenderItem(onloadData.musicByTag, "onloadData.musicByTag");
    renderAlbums(onloadData.albumsLoad);

    // -------------------------------------- //
    $(".top-1-show").html(
      `<img src="${checkImg(onloadData.top3Music[0].imgPath)}" alt="no-img">`
    );
    $(".top-table").html(top3MusicComponent);
    $(".new-music-box").html(newMusicComponent);
  </script>
</body>