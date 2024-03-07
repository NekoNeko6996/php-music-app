function loadItemShowUser(userArray) {
  var htmlComponent = `
    <tr class="user-table-tr">
      <th class="user-table-th">USER ID</th>
      <th class="user-table-th">USER EMAIL</th>
      <th class="user-table-th">USER PERMISSION</th>
      <th class="user-table-th">USER OPTION</th>
    </tr>
  `;
  userArray.forEach((row, index) => {
    htmlComponent += `
    <tr class="user-table-tr">
      <td class="user-table-td">${row[0]}</td>
      <td class="user-table-td">${row[1]}</td>
      <td class="user-table-td">${row[2]}</td>
      <td class="user-table-td">
        <button class="user-option-btn">BLOCK USER</button>
        <button class="user-option-btn">DELETE USER</button>
      </td>
    </tr>`;
  });
  $("#user-display-table").html(htmlComponent);
}

$("document").ready(() => {
  $.ajax({
    url: "server/adminPage.php",
    type: "POST",
    data: {
      requestCode: 1,
      data: "",
    },
    success: (response) => {
      const onloadDataResponse = JSON.parse(
        response.replace("<!-- SERVER -->", "")
      );
      loadItemShowUser(onloadDataResponse.userList);
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
});

function loadMusicData() {
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
        const musicDataResponse = JSON.parse(
          response.replace("<!-- SERVER -->", "")
        );
        if (musicDataResponse) {
          $("#update-music-name").val(musicDataResponse[0][0]);
          $("#update-music-path").val(musicDataResponse[0][1]);
          $("#update-music-author").val(musicDataResponse[0][2]);
          $("#update-img-path").val(musicDataResponse[0][3]);
          $("#update-gif-path").val(musicDataResponse[0][4]);
          $("#update-music-tag").val(musicDataResponse[0][5]);
        }
      },
      error: (status, error) => {
        console.error(status, error);
      },
    });
  } else console.log("[ERROR] musicID must be greater than 0");
}

function uploadMusic(event) {
  // event.preventDefault();
  const formData = $("#uploadMusic-form").serialize();

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
}
