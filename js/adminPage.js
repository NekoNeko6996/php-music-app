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
        // console.log(response);
        const onloadDataResponse = JSON.parse(response);
        console.log(onloadDataResponse);
        loadItemShowUser(onloadDataResponse.userList);
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  });
}

// load music to update
function loadMusicData(event) {
  event.preventDefault();
  const musicID = $("#update-music-id").val();
  if (musicID >= 1) {
    $.ajax({
      url: "server/adminPage.php",
      type: "POST",
      data: {
        requestCode: 3,
        musicID,
      },
      success: (response) => {
        console.log(response);
        const musicDataResponse = JSON.parse(response);
        if (musicDataResponse) {
          // $("#update-music-name").val(musicDataResponse[0].musicName);
          // $("#update-music-path").val(musicDataResponse[0].musicPath);
          // $("#update-music-author").val(musicDataResponse[0].author);
          // $("#update-img-path").val(musicDataResponse[0].imgPath);
          // $("#update-gif-path").val(musicDataResponse[0].gifPath);
          // $("#update-music-tag").val(musicDataResponse[0].tag);
        }
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  } else console.log("[ERROR] musicID must be greater than 0");
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

// update music
function updateMusicRequest(event) {
  event.preventDefault();
  let formData = $("#update-music-form-data").serializeArray();

  getDuration(formData[2].value, (duration) => {
    formData.push({ name: "duration", value: duration });
    formData = $.param(formData);

    $.ajax({
      url: "server/adminPage.php",
      type: "POST",
      data: {
        requestCode: 4,
        formData,
      },
      success: (response) => {
        console.log(response);
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
