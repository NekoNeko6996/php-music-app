<body>
  <section class="albums-session">
    <div class="albums-info-container">
      <img alt="no-img" id="album-img-info" />
      <p id="album-name-p">Moon Halo Honkai Impact 3rd</p>
      <p id="album-author-p">MiHoYo - Honkai Impact 3rd</p>
      <button class="album-play-btn" onclick="playAlbumChecked()">Play</button>
      <span> </span>
    </div>
    <div class="albums-show-items-container">
      <span class="fl-row-space-between albums-list-title">
        <p>Music</p>
        <p>Duration</p>
      </span>

      <div id="al-child-contain">
        <!-- albums list show here -->
      </div>
    </div>
  </section>

  <script>
    var albumsChecked = [];
    var onloadAlbumsList = [];

    if (!myAlbum) {
      var albumsData = onloadData.albumsLoad;
      var albumID = albumsData[albumIndex].albumID;
    } else {
      var albumsData = onloadData.userAlbumsList;
      var albumID = albumsData[albumIndex].id;
    }

    function addToAlbumsChecked(music) {
      const exist = albumsChecked.some(
        (child) => JSON.stringify(child) === JSON.stringify(music)
      );
      if (!exist) {
        albumsChecked.push(music);
      } else {
        const index = albumsChecked.findIndex(
          (child) => JSON.stringify(child) === JSON.stringify(music)
        );
        if (index != -1) {
          albumsChecked.splice(index, 1);
        }
      }
    }

    function playAlbumChecked() {
      if (albumsChecked.length > 0) {
        clickToListen(0, albumsChecked);
      } else {
        clickToListen(0, onloadAlbumsList);
      }
    }

    function secToMinutes(sec) {
      return `${Math.round(sec / 60)
        .toString()
        .padStart(2, "0")}:${Math.round(sec % 60)
          .toString()
          .padStart(2, "0")}`;
    }

    function renderInfoAlbum(info) {
      $("#album-img-info").attr("src", info.albumImgPath);
      $("#album-name-p").text(info.albumName);
      if (myAlbum) $("#album-author-p").text("Date " + info.createAt);
      else $("#album-author-p").text(info.author);
    }

    function loadAlbumItems(list) {
      var htmlComponent = "";

      if (!list.length == 0)
        list.forEach((row, index) => {
          htmlComponent += `
          <div class="al-sh-items-box">
            <input type="checkbox" name="check-to-play-music-${row.imgPath
            }" id="check-to-play-music-${row.id
            }" onchange='addToAlbumsChecked(${JSON.stringify(row)})'>
            <label for="check-to-play-music-${row.id}"></label>
            <img src="${checkImg(row.imgPath)}" alt="no-img" />
            <span>
              <span class="item-info-name">
                <p>${row.musicName}</p>
                <p>${row.author || "UnKnown"}</p>
              </span>

              <button class="al-btn-svg" onclick='addToPlaylist(${JSON.stringify(
              row
            )})'>
                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none">
                  <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="white" stroke-width="1.5"/>
                  <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </button>

              ${myAlbum
              ? `
                  <button class="al-btn-svg al-btn-svg-delete" onclick='deleteFromMyAlbum(${row.id})'>
                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10 12V17" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M14 12V17" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M4 7H20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                  `
              : ""
            }

              <p class="music-album-duration">${secToMinutes(row.duration)}</p>
            </span>
          </div>
        `;
        });
      else
        htmlComponent = `
        <h1 class="nothing-text">Hmm... Nothing to see here</h1>

      `;

      $("#al-child-contain").html(htmlComponent);
    }

    $("document").ready(() => {
      $.ajax({
        url: "server/server.php",
        type: "POST",
        data: {
          requestCode: 5,
          albumID,
          myAlbum,
        },
        success: (response) => {
          if (response) {
            onloadAlbumsList = JSON.parse(
              response.replace("<!-- Server -->", "")
            );
            loadAlbumItems([...onloadAlbumsList]);
            if (albumsData[albumIndex]) {
              renderInfoAlbum(albumsData[albumIndex]);
            }
          }
        },
        error: (status, error) => {
          console.error(status, error);
        },
      });
    });

    function deleteFromMyAlbum(musicID) {
      $.ajax({
        url: "server/server.php",
        type: "POST",
        data: {
          requestCode: 8,
          musicID,
          albumID,
        },
        success: (response) => {
          const res = JSON.parse(response);
          if (res.status) {
            loadAlbumItems(res.newList);
          }
        },
        error: (status, error) => {
          console.error(status, error);
        },
      });
    }
  </script>
</body>