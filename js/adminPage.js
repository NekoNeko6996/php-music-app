function uploadMusic(event) {
  event.preventDefault();
  const formData = $("#uploadMusic-form").serialize();

  $.ajax({
    url: "server/adminPage.php",
    type: "POST",
    data: formData,
    success: (response) => {
      console.log(JSON.parse(response));
    },
    error: (status, error) => {
      console.error(status, error);
    },
  });
}
