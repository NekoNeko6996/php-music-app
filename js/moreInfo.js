function getDuration(path, callback) {
  if (path) {
    let audioDuration = new Audio(path);
    audioDuration.oncanplay = () => {
      callback(Math.floor(audioDuration.duration));
    };
  } else console.error("[getPath ERROR]");
}

// update music
function updateMusicRequest(event) {
  event.preventDefault();

  const param = new URLSearchParams(window.location.search);

  const id = param.get("id");
  let formData = $("#update-music-form-data").serializeArray();

  console.log(formData);

  getDuration($('#musicPath').val(), (duration) => {
    formData.push({ name: "duration", value: duration });
    formData.push({ name: "musicId", value: id });
    formData = $.param(formData);

    console.log(formData);

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

$("#audioFile").on("change", (event) => {
  const file = event.target.files[0]; //lấy file

  if (!file) {
    console.error("[ERROR LOAD FILE AUDIO]");
    return;
  }
  const src = URL.createObjectURL(file);

  $(".new-source-span").html(`
    <h3>New</h3>
    <audio controls id="audio-control"></audio>
  `);

  $("#audio-control").attr("src", src);

  $("#audio-control").on("ended", () => {
    URL.revokeObjectURL(src);
  });
});

$("#imageFile").on("change", (event) => {
  const file = event.target.files[0]; //lấy file

  if (!file) {
    console.error("[ERROR LOAD FILE IMAGE]");
    return;
  }
  const src = URL.createObjectURL(file);

  $("#new-img-preview").html(
    `
    <h3>New</h3>
    <img src="${src}" id="music-img" alt="please choose file to see this">
    `
  );
});
