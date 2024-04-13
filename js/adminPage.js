if ($("#user-display-table") != null) {
  function loadItemShowUser(userArray) {
    var htmlComponent = `
      <tr class="user-table-tr">
        <th class="user-table-th">USER ID</th>
        <th class="user-table-th">USER EMAIL</th>
        <th class="user-table-th">USER NAME</th>
        <th class="user-table-th">USER PERMISSION</th>
        <th class="user-table-th"></th>
      </tr>
    `;
    userArray.forEach((row, _) => {
      htmlComponent += `
      <tr class="user-table-tr">
        <td class="user-table-td">${row.id}</td>
        <td class="user-table-td">${row.email}</td>
        <td class="user-table-td">${row.username}</td>
        <td class="user-table-td">${row.permissionName}</td>
        <td class="user-table-td flex-right">
          ${
            row.permissionName != "root"
              ? `<button type="button" class="user-option-btn block-btn" onclick="userAction(${
                  row.id
                }, 5)">${
                  row.block == 1 ? "UNBLOCK USER" : "BLOCK USER"
                }</button>
                <button type="button" class="user-option-btn delete-btn" onclick="userAction(${
                  row.id
                }, 6)">DELETE USER</button>`
              : ""
          }
        </td>
      </tr>`;
    });
    $("#user-display-table").html(htmlComponent);
  }

  // ------------------------------------------- //
  var currentMusicList = [];
  var pageLimit = 12;
  var currentPage = 1;

  function uploaderLoadMusicItemToShow(data, page, limit) {
    if (data) currentMusicList = [...data];

    var html = "";
    if (currentMusicList.length > 0) {
      $(".pagination-child-button").removeClass("btn-select");
      $(".pagination-child-button")
        .eq(page - 1)
        .addClass("btn-select");

      for (
        let count = (page - 1) * limit;
        count < limit * page && count < currentMusicList.length;
        count++
      ) {
        html += `
        <tr>
          <td>${count + 1}</td>
          <td>
            <p class="uploader-show-item-p">${
              currentMusicList[count].musicName
            }</p>
          </td>  
          <td>
            <p class="uploader-show-item-p">${
              currentMusicList[count].author
            }</p>
          </td> 
          <td>
            <p class="uploader-show-item-p">${currentMusicList[count].tag}</p>
          </td> 
          <td>
            <button type="button" class="normal-btn more-info-btn" onclick="redirectToMusicInfo(${
              currentMusicList[count].id
            })">More Info</button>
          </td>
        </tr>
        `;
      }
    }

    $(".uploader-show-music-area").html(html);
  }

  function changePage(page) {
    if (page > 0 && page <= Math.ceil(currentMusicList.length / pageLimit)) {
      currentPage = page;
      uploaderLoadMusicItemToShow(currentMusicList, page, pageLimit);
    }
    console.log(currentPage);
  }
  // -------------------------------------------------------------------- //

  // onload
  $("document").ready(() => {
    $.ajax({
      url: "server/adminPage.php",
      type: "POST",
      data: {
        requestCode: 1,
        data: "",
      },
      success: (response) => {
        var html =
          "<button class='pagination-child-button-control previous' onclick='changePage(currentPage - 1)'>Previous</button>";
        const onloadDataResponse = JSON.parse(response);
        loadItemShowUser(onloadDataResponse.userList);

        // pagination
        for (
          let page = 1;
          page <= Math.ceil(onloadDataResponse.musicList.length / pageLimit);
          page++
        ) {
          html += `<button class="pagination-child-button" onclick="changePage(${page})">${page}</button>`;
        }
        html +=
          "<button class='pagination-child-button-control next' onclick='changePage(currentPage + 1)'>Next</button>";
        if (html) $(".pagination-container").html(html);
        //

        uploaderLoadMusicItemToShow(onloadDataResponse.musicList, 1, pageLimit);
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  });
}

// ------------------------------------------------------------------- //
function redirectToMusicInfo(id) {
  window.location.href = "moreInfo.php?id=" + encodeURIComponent(id);
}
// -------------------------------------------------------- //
function userAction(userID, code) {
  if (userID) {
    $.ajax({
      url: "server/adminPage.php",
      type: "POST",
      data: {
        requestCode: code,
        userID,
      },
      success: (response) => {
        console.log(response);
        window.location.reload();
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  }
}

// ---------------------------------------------------- //
function searchUser(event) {
  if (event) event.preventDefault();
  const email = $("#search-user-input").val();

  $.ajax({
    url: "server/adminPage.php",
    type: "POST",
    data: {
      requestCode: 7,
      email,
    },
    success: (response) => {
      const result = JSON.parse(response);
      loadItemShowUser(result);
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
  console.log(email);
}

// -----------------------------------------------------//
function reload() {
  window.location.reload();
}
