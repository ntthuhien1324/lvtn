<!-- Main Header -->
<header class="main-header">
    <style>
        .navbar-default .navbar-toggle .icon-bar {background-color: white;}
        /*.active{
            background-color: blue;*/
        .dropdown-menu>li>a{color: black;}
        .dropdown-menu>li>a{background-color: whitesmoke;}
        /* Trigger bootstrap navbar collapse pada viewport <= 1200px */
@media (max-width: 1200px) {
    .navbar-header {
        float: none;
    }
    .navbar-default .navbar-toggle:focus, .navbar-default .navbar-toggle:hover {
    background-color: transparent;
}
    .navbar-left,
    .navbar-right {
        float: none !important;
    }

    .navbar-toggle {
        display: block;
    }

    .navbar-collapse {
        border-top: 1px solid transparent;
       
    }

    .navbar-fixed-top {
        top: 0;
        border-width: 0 0 1px;
    }

    .navbar-collapse.collapse {
        display: none!important;
    }

    .navbar-nav {
        float: none!important;
        margin-top: 7.5px;
    }

    .navbar-nav>li {
        float: none;
    }

    .navbar-nav>li>a {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .collapse.in{
        display:block !important;
    }
  

    /* Hapus gap 15px pada .navbar-collapse */
    .navbar .navbar-nav {
      margin-left: -15px;
      margin-right: -15px;
    }

    /* Merapihkan dropdown menu: Warna, posisi dll */
    .navbar-nav .open .dropdown-menu {
        position: static;
        float: none;
        width: auto;
        margin-top: 0;
        background-color: transparent;
        border: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    
}

@media screen and (min-width: 768px) {
    /* Rubah behaviour .container */
  .navbar .container {
    margin-left: auto;
    margin-right: auto;
    padding: 0;
    max-width: 1170px;
    width: initial;
  }
  
  .navbar > .container .navbar-brand {
    margin-left: 0;
  }
  
  .navbar .container .navbar-header {
    margin-left: 0;
    margin-right: 0;
  }
 
}
@media screen and (max-width: 1200px){
     .navbar-right .dropdown-menu {
    left: 0;
    left: auto;
    }
}
.skin-blue-light .main-header .navbar .dropdown-menu li a {
    color: black;
}
    </style>
    <nav class="navbar navbar-default navbar-fixed-top" style="margin-left: 0px;">
      <div class="container-flud">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a href="{{ url('user/sinh-vien') }}" class="logo" style="width: auto;">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                 <span class="logo-mini"><img src="../../../uploads/images/logo.png" height="50px;"></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src="../../../uploads/images/logo.png" height="50px;"></span>

            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav ">
            <li class="active"><a href="{{ url('user/sinh-vien') }}" ><i class="fa fa-home fa-fw fa-1x" aria-hidden="true"></i> Trang chủ <span class="sr-only">(current)</span></a></li>
                    <li class="dropdown-register">
                         <a href="javascript:void(0);" class="dropdown-toggle" data-toggle=""><strong><i class="fa fa-pencil-square-o fa-fw fa-1x"></i> Đăng ký</strong> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('user/dang-ky-mon-hoc') }}"><i class="fa fa-pencil-square-o fa-1x"></i><strong>Đăng ký môn học</strong></a></li>
                            <li><a href="{{ url('user/dang-ky-hoc-lai') }}"><i class="fa fa-pencil-square fa-1x"></i><strong>Đăng ký học cải thiện, học lại</strong></a></li>
                            <li><a href="{{ url('user/dang-ky-ngoai-ke-hoach') }}"><i class="fa fa-pencil fa-1x"></i><strong>Đăng ký ngoài kế hoạch</strong></a></li>
                            
                        </ul>
                       
                    </li>
                    <li><a href="{{ url('user/ket-qua-dang-ky') }}"><i class="fa fa-th-list fa-fw fa-1x" aria-hidden="true"></i> Kết quả đăng ký</a></li>
                    <li><a href="{{ url('user/xem-diem') }}"><i class="fa fa-list fa-fw fa-1x" aria-hidden="true"></i> Xem điểm</a></li>
                    <li><a href="{{ url('user/mon-hoc-song-song') }}"><i class="fa fa-list fa-fw fa-1x" aria-hidden="true"></i> Xem môn song song</a></li>
                    <li><a href="{{ url('user/mon-hoc-truoc-sau') }}"><i class="fa fa-list fa-fw fa-1x" aria-hidden="true"></i> Xem môn trước</a></li>
                    <li><a href="{{ url('user/gop-y') }}"><i class="fa fa-paper-plane fa-fw fa-1x" aria-hidden="true"></i> Góp ý kiến</a></li>

                    @if(Auth::check())
                    </ul>

                      <ul class="nav navbar-nav navbar-right" style="margin-right: 0px;">

                        <li class="dropdown-register">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Xin chào {{Auth::User()->last_name}}<img src="" class="user-image"><span class="caret"></span></a>
                             <ul class="dropdown-menu">
                                 @php $id = Auth::User()->id @endphp
                                    <li><a href="{{ url('user/thong-tin/'. $id.'/edit') }}"><i class="fa fa-user fa-1x" aria-hidden="true"></i>Thông tin cá nhân</a></li>
                                   <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-1x" aria-hidden="true"></i>Đăng xuất</a></li>
                            </ul>
                         </li>
                      </ul>
                    @endif
        </div><!--/.nav-collapse -->
      </div>
    </nav>
</header>

<script type="text/javascript">
    $('li.dropdown-register').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).fadeIn(500);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).fadeOut(500);
});
</script>