// -------------------------- music wave ---------------------------- //
const array = [];
const array_row = [];

//model của columns
const model = "normal"; // normal or 'smooth'
//số cột
const columns = 25;
//số ô màu
const rows = 10;
const index = 0.8; //thay dổi độ nhạy của sóng
//màu
const color = [
  "#5e2566",
  "#6e2b77",
  "#863691",
  "#963da1",
  "#ba4dc9",
  "#dd60ee",
  "#df79ec",
  "#e990f5",
  "#e8acf0",
];
var source, dataArray, audioCtx, analyser, bufferLength;
var status_ = false;

function musicWave(interact, audioElement) {
  if (interact && audioElement) {
    // khởi tạo 1 lần duy nhất
    if (!status_) {
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      source = audioCtx.createMediaElementSource(audioElement);
      analyser = audioCtx.createAnalyser();
      source.connect(analyser);
      analyser.connect(audioCtx.destination);

      analyser.fftSize = 2048;
      analyser.smoothingTimeConstant = 0.75;

      bufferLength = analyser.frequencyBinCount;
      dataArray = new Uint8Array(bufferLength);

      function initialization() {
        for (let i = 0; i < columns; i++) array.push("");
        for (let j = 0; j < rows; j++) array_row.push("");
      }
      initialization();
      status_ = true;
    }

    function updateVisualizer() {
      requestAnimationFrame(updateVisualizer);
      if (audioElement.play) {
        analyser.getByteFrequencyData(dataArray);
        const average = [];
        for (let i = 0; i < columns; i++) {
          average[i] = dataArray[i * Math.floor(800 / columns)];
        }

        for (let i = 0; i < columns; i++) {
          const element = document.querySelector(`[data-index="${i + 2000}"]`);
          if (element) {
            element.style.height = `${Math.floor(
              (average[i] % 100) * index
            )}px`;
          }
        }
      }
    }

    function draw() {
      // Add cột vào container
      const html_columns = array
        .map((data, index) => {
          return `<div class="grid-item-Wave" data-index="${
            index + 2000
          }"></div>`;
        })
        .join("");
      $("#WaveContainer").html(html_columns);

      if (model != "smooth") {
        const html_rows = array_row
          .map((data, index) => {
            return `<div class="grid-item-row-Wave" data-index="${
              index + 1000
            }" style="bottom: ${index * 10}px; background-color: ${
              color[index]
            };"></div>`;
          })
          .join("");

        for (let i = 0; i < columns; i++) {
          $(`[data-index="${i + 2000}"]`).html(html_rows);
        }

        $(".grid-item-Wave").css("grid-template-rows", `repeat(${rows}, 19px)`);
      }

      $("#WaveContainer").css(
        "grid-template-columns",
        `repeat(${columns}, 12px)`
      );
    }
    draw();
    updateVisualizer();
  }
}
