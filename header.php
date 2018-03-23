<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <div class="navbar-collapse collapse" id="menu">
        <ul class="nav navbar-nav">
        <li><a href="#">Trang chủ</a></li>
        <?php if ($logged) { ?>
            <li><a href="#" id="logout-link" data-toggle="modal">Đăng xuất</a></li>
        <?php } else { ?>
            <li><a href="#" data-toggle="modal" data-target="#loginModal">Đăng nhập</a></li>
        <?php } ?>
        <li><a href="#" data-toggle="modal" data-target="#searchModal">Tìm kiếm</a></li>
        </ul>
    </div>
  </div>
</nav>