<body>
  <h3 class="albums-title">Albums</h3>
  <div class="mid-banner">
    <!-- albums show here -->
  </div>

  <h3 class="new-music-title" style="color: var(--text-color)">New Release</h3>
  <div class="new-music-box"></div>

  <h3 class="top-music-title">Top Music</h3>
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
    <div class="about-us">
      <h2>About Us</h2>
      <p>Website: <a target="_blank" href="https://cusc.ctu.edu.vn">https://cusc.ctu.edu.vn</a></p>
      <p>Phone: 0912345678</p>
      <p>Address:
        <a target="_blank"
          href="https://www.google.com/maps/place/CUSC+-+Cantho+University+Software+Center/@10.0335023,105.7795289,18.75z/data=!4m14!1m7!3m6!1s0x31a0881f9a732075:0xfa43fbeb2b00ca73!2sCUSC+-+Cantho+University+Software+Center!8m2!3d10.0336437!4d105.7795715!16s%2Fg%2F1w0qyd_6!3m5!1s0x31a0881f9a732075:0xfa43fbeb2b00ca73!8m2!3d10.0336437!4d105.7795715!16s%2Fg%2F1w0qyd_6?entry=ttu">
          1 Đ. Lý Tự Trọng, An Phú, Ninh Kiều, Cần Thơ, Vietnam
        </a>
      </p>
      <p>Email Contact: nhnama23030@cusc.ctu.edu.vn</p>
      <br>
      <br>
      <br>
      <br>
      <br>
      <p>Powered By Nguyễn Hoàng Nam</p>
      <p>Version: 1.0</p>
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