<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <title>Đăng nhập cho sinh viên</title>
    <base href="{{asset('')}}">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar"  style="background-color: whitesmoke;">
<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- Main wrapper  -->
<div class="container-fluid">
    <div class="row">


        <div class="col-lg-2 col-md-12 col-sm-12" style="margin-top: auto;margin-bottom: auto;">
            <div class="row card">
                <img src="../uploads/images/logo.png" style="height: 200px;width:100%">
            </div>
        </div>
        <div class="col-lg-10 card col-md-12 col-sm-12" >
            <img src="../uploads/images/banner-top.png" style="height: 200px;width:100%">
        </div>


    </div>
</div>
<div class="container-fluid">
    <div class="row" style="margin-top: -30px;">

        <div class="col-lg-9">

            <div class="login-content card">


                <h1 class="text-center"><b>THÔNG BÁO</b></h1>
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark" >
                    <tr align="center">
                        <th>Tên thông báo</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($thongBao as $value)
                        <tr>
                            <?php if ($value->url) {?>
                            <td><a href="uploads/{{$value->url}}"</a> {{$value->ten}}</td>
                            <?php } else {
                                ?>
                                <td><a "</a> {{$value->ten}}</td>
<?php
                                } ?>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <div class="pagination" style="justify-content: flex-end;">

                    {{$thongBao->links()}}
                </div>

            </div>

        </div>
        <div class="col-lg-3">
            <div class="login-content card" style="margin-right: -15px;">
                <div class="login-form">
                    <h1 class="text-center"><b>Đăng nhập</b></h1>

                    <form action="postLogin" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="form-group">
                            <label>Tài khoản</label>
                            <input type="text" name="mssv" class="form-control" placeholder="Mã số sinh viên">
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                        </div>
                        <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Đăng nhập</button>
                    </form>
                    @if(count($errors)>0)
                        <div class="alert alert-danger text-center">
                            @foreach($errors->all() as $err)
                                {{$err}}<br>
                            @endforeach
                        </div>
                    @endif

                    @if(session('notification'))
                        <div class="alert alert-danger text-center">
                            {{session('notification')}}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


<!-- End Wrapper -->
<!-- All Jquery -->
<script src="js/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<!--Menu sidebar -->
<!--stickey kit -->

<!--Custom JavaScript -->
<script src="js/custom.min.js"></script>
<script type="text/javascript">

</script>
</body>

</html>