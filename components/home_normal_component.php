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
      <div class="category-tag" onclick="selectTag(event, 'all')">
        All
      </div>
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

    <div class="pagination-container">
      <div class="pagination-child-button-control br-left" onclick="loadMusicPage(musicPage - 1, 30)">Previous</div>
      <div class="pagination-child-button-show">
        <!-- change page button show here -->
      </div>
      <div class="pagination-child-button-control br-right" onclick="loadMusicPage(musicPage + 1, 30)">Next</div>
    </div>
  </div>

  <footer class="home-normal-footer">
    <div>
      <h2>About Us</h2>
      <p>Address: Number 1 Ly Tu Trong Street - CanTho City</p>
      <p>Email Contact: nhnama23030@cusc.ctu.edu.vn</p>
      <p>Phone Number: 0563938131</p>
    </div>
    <div>
      <h2>Music Partner</h2>
      <span>
        <a href="https://youtube.com/" target="_blank"><img src="assets/partner_logo/youtube.png" alt="youtube"></a>
        <a href="https://zingmp3.vn/" target="_blank"><img src="assets/partner_logo/zingmp3.png" alt="zing-mp3"></a>
      </span>
    </div>
    <div class="join-with-us-container">
      
    </div>
  </footer>
  <script src="components/js/home_normal_component.js"></script>
</body>