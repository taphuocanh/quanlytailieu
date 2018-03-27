<section class="container-fluid top-header">
    <div class="row">
        <div class="col-md-6">
            <a href="http://qlgd.huaf.edu.vn/quanly" title="Trang chủ Quản lý">
                <div id="logo"></div>
            </a>
        </div>
        <div class="col-md-6"></div>
    </div>
</section>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
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
        <?php if (!$logged) { ?> 
        <div id="filter-select-box" class="form-inline pull-right">
            <div class="form-group">
                <label>Lọc theo đơn vị:</label> 
            </div>
        </div>
        <?php } ?>
    </div>
  </div>
</nav>
<section class="container-fluid page-title">
    <div class="row">
        <div class="col-md-12">
            <h2>Quản lý tài liệu</h2>
        </div>
    </div>
</section>