// function api(requestCode, data, callback) {

//   $.ajax({
//     url: "server/adminPage.php",
//     type: "POST",
//     data: {
//       requestCode,
//       [data.name]: data
//     },
//     success: (response) => callback(response),
//     error: (status, error) => {
//       console.error(status, error);
//     },
//   });
// }

if ($("#user-display-table") != null) {
  function loadItemShowUser(userArray) {
    var htmlComponent = `
      <tr class="user-table-tr">
        <th class="user-table-th">USER ID</th>
        <th class="user-table-th">USER EMAIL</th>
        <th class="user-table-th">USER NAME</th>
        <th class="user-table-th">USER PERMISSION</th>
        <th class="user-table-th">USER OPTION</th>
      </tr>
    `;
    userArray.forEach((row, index) => {
      htmlComponent += `
      <tr class="user-table-tr">
        <td class="user-table-td">${row.id}</td>
        <td class="user-table-td">${row.email}</td>
        <td class="user-table-td">${row.username}</td>
        <td class="user-table-td">${row.permissionName}</td>
        <td class="user-table-td flex-right">
          ${
            row[3] != "root"
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
        console.log(response);
        const onloadDataResponse = JSON.parse(response);
        console.log(onloadDataResponse);
        loadItemShowUser(onloadDataResponse.userList);
        uploaderLoadMusicItemToShow(onloadDataResponse.musicList);
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  });
}

// ------------------------------------------------------------------- //
$(document).ready(() => {
  $(".uploader-show-music-area").on(
    "click",
    ".uploader-update-btn",
    (event) => {
      var row = $(this).closest("tr");
      var musicName = row.find(".uploader-td-music-name input").val();
      var author = row.find("td:eq(1) input").val();
      var tag = row.find("td:eq(2) input").val();

      // Here, you can perform the update operation using the retrieved data
      console.log("Music Name:", musicName);
      console.log("Author:", author);
      console.log("Tag:", tag);
    }
  );
});

function redirectToMusicInfo(id) {
  window.location.href = "moreInfo.php?id=" + encodeURIComponent(id);
}

function uploaderLoadMusicItemToShow(data) {
  var html = "";
  if (data.length > 0) {
    data.forEach((row, idx) => {
      html += `
      <tr>
        <td>
          <p class="uploader-show-item-p">${row.musicName}</p>
        </td>  
        <td>
          <p class="uploader-show-item-p">${row.author}</p>
        </td> 
        <td>
          <p class="uploader-show-item-p">${row.tag}</p>
        </td> 
        <td>
          <button type="button" class="normal-btn more-info-btn" onclick="redirectToMusicInfo(${row.id})">More Info</button>
        </td>
      </tr>
      `;
    });
  }

  $(".uploader-show-music-area").html(html);
}

function getDuration(path, callback) {
  if (path) {
    let audioDuration = new Audio(path);
    audioDuration.oncanplay = () => {
      callback(Math.floor(audioDuration.duration));
    };
  } else console.error("[getPath ERROR]");
}

// upload music
function uploadMusic(event) {
  event.preventDefault();

  var formData = $("#uploadMusic-form").serializeArray();
  getDuration(formData[1].value, (duration) => {
    formData.push({ name: "duration", value: duration });
    formData = $.param(formData);

    $.ajax({
      url: "server/adminPage.php",
      type: "POST",
      data: {
        requestCode: 2,
        formData,
      },
      success: (response) => {
        console.log(JSON.parse(response));
        window.location.reload();
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  });
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
