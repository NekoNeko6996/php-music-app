// music item
function loadMusicItemByTag(imgUrl, musicName, author, index, nameArray, color, musicID) {
  return `
    <div class="category-item-box">
    <div class="play-svg" onclick="clickToListen(${index}, ${nameArray})">
      <svg
        width="30px"
        height="30px"
        viewBox="0 0 24 24"
        fill="${color}"
        onclick="addLibraryClick(${musicID})"
      >
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z"
          stroke="white"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
      <!--  -->
      <svg width="50px" height="50px" viewBox="0 0 24 24" fill="none">
        <path
          d="M16.6582 9.28638C18.098 10.1862 18.8178 10.6361 19.0647 11.2122C19.2803 11.7152 19.2803 12.2847 19.0647 12.7878C18.8178 13.3638 18.098 13.8137 16.6582 14.7136L9.896 18.94C8.29805 19.9387 7.49907 20.4381 6.83973 20.385C6.26501 20.3388 5.73818 20.0469 5.3944 19.584C5 19.053 5 18.1108 5 16.2264V7.77357C5 5.88919 5 4.94701 5.3944 4.41598C5.73818 3.9531 6.26501 3.66111 6.83973 3.6149C7.49907 3.5619 8.29805 4.06126 9.896 5.05998L16.6582 9.28638Z"
          stroke="white"
          stroke-width="2"
          stroke-linejoin="round"
        />
      </svg>
      <!--  -->
      <svg width="30px" height="30px" viewBox="0 0 24 24" fill="white">
        <circle
          cx="7"
          cy="12"
          r="1.5"
          stroke="white"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <circle
          cx="12"
          cy="12"
          r="1.5"
          stroke="white"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <circle
          cx="17"
          cy="12"
          r="1.5"
          stroke="white"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </div>
    <img src="${imgUrl? imgUrl : "music/img/default.jpg"}" alt="no-img" />
    <p class="music-name">${musicName}</p>
    <p class="author">${author || "Unknown"}</p>
  </div>
`;
}
