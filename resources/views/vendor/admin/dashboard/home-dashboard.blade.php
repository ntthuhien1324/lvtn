<head>
    <script src="{{ admin_asset("/vendor/laravel-admin/chartjs/chart.js")}}"></script>
</head>
<div class="title">
    <b>QUẢN LÝ ĐĂNG KÝ MÔN HỌC VÀ ĐIỂM</b>
</div>
<div class="container-fluid box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Thống kê</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="row box-body">
        <div class="col-lg-2 col-sm-6 col-md-6">
            <div class="circle-tile">
                <a href="/admin/sinh-vien" target="_blank">
                    <div class="circle-tile-heading dark-blue">
                        <i class="fa fa-users fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded">
                        Sinh viên
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{--265--}}
                        {{$countUserSV}}
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <a href="/admin/sinh-vien" target="_blank" class="circle-tile-footer">Xem chi tiết <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-6">
            <div class="circle-tile">
                <a  href="/admin/user_gv" target="_blank" >
                    <div class="circle-tile-heading green">
                        <i class="fa fa-users fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content green">
                    <div class="circle-tile-description text-faded">
                        Users GV
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{$countGV}}
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <a href="/admin/user_gv" target="_blank" class="circle-tile-footer">Xem chi tiết <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-6">
            <div class="circle-tile">
                <a href="/admin/user_admin" target="_blank" >
                    <div class="circle-tile-heading orange">
                        <i class="fa fa-users fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content orange">
                    <div class="circle-tile-description text-faded">
                        User QT
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{$countUserQuanTri}}
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <a href="/admin/user_admin" target="_blank" class="circle-tile-footer">Xem chi tiết <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-6">
            <div class="circle-tile">
                <a href="/admin/lop" target="_blank">
                    <div class="circle-tile-heading blue">
                        <i class="fa fa-book fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content blue">
                    <div class="circle-tile-description text-faded">
                        Lớp
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{$countLop}}
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                    <a href="/admin/lop" target="_blank" class="circle-tile-footer">Xem chi tiết <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-6">
            <div class="circle-tile">
                <a href="/admin/dot-dang-ky" target="_blank">
                    <div class="circle-tile-heading red">
                        <i class="fa fa-pencil-square-o fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content red">
                    <div class="circle-tile-description text-faded">
                        Đợt ĐK
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{$countDotDangKy}}
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </div>
                    <a href="/admin/dot-dang-ky" target="_blank" class="circle-tile-footer">Xem chi tiết <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-md-6">
            <div class="circle-tile">
                <a href="/admin/lop-hoc-phan" target="_blank">
                    <div class="circle-tile-heading purple">
                        <i class="fa fa-bookmark fa-fw fa-3x"></i>
                    </div>
                </a>
                <div class="circle-tile-content purple">
                    <div class="circle-tile-description text-faded">
                        Lớp HP
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{$countLopHocPhan}}
                        <i class="fa fa-bookmark" aria-hidden="true"></i>
                    </div>
                    <a href="/admin/lop-hoc-phan" target="_blank" class="circle-tile-footer">Xem chi tiết <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Biểu đồ</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="row box-body">
        <div class="col-lg-6 col-sm-6 col-md-6">
            <canvas id="myChart"></canvas><br>
            <div style="text-align: center; font-weight: bold; font-size: large">Biểu đồ số lượng sinh viên</div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6">
            <canvas id="myLineChart"></canvas><br>
            <div style="text-align: center; font-weight: bold; font-size: large">Biểu đồ số lượt đăng ký</div>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            labels: {{$arrNamHoc}} ,
            datasets: [{
                label: 'Số lượng',
                // data: [12, 19, 3, 5, 2, 3],
                data: {{$countSV}},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    var ctx = document.getElementById("myLineChart").getContext('2d');
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            // labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            labels: <?php echo $dotDangKy ?>,
            datasets: [{
                label: 'Lượt đăng ký ',
                // data: [12, 19, 3, 5, 2, 3],
                data: {{$dataDotDangKy}},
                backgroundColor: [

                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [

                    'rgba(54, 162, 235, 1)'

                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>

<style>
    .title {
        font-size: 50px;
        color: #636b6f;
        font-weight: 100;
        display: block;
        text-align: center;
        margin: 0px 0 10px 0px;
    }

    .circle-tile-heading .fa {
        line-height: 80px;
    }
    .circle-tile-description {
        text-transform: uppercase;
    }
    .circle-tile-heading {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto -40px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 100%;
        color: #fff;
        transition: all ease-in-out .3s;
    }
    .circle-tile {
        margin-bottom: 15px;
        text-align: center;
    }
    .circle-tile-content {
        padding-top: 50px;
    }
    .text-faded {
        color: rgba(255,255,255,0.7);
    }
    .circle-tile-footer:hover {
        text-decoration: none;
        color: rgba(255,255,255,0.5);
        background-color: rgba(0,0,0,0.2);
    }
    .circle-tile-number {
        padding: 5px 0 15px;
        font-size: 26px;
        font-weight: 700;
        line-height: 1;
    }
    .circle-tile-heading {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto -40px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 100%;
        color: #fff;
        transition: all ease-in-out .3s;
    }
    .circle-tile-footer {
        display: block;
        padding: 5px;
        color: rgba(255,255,255,0.5);
        background-color: rgba(0,0,0,0.1);
        transition: all ease-in-out .3s;
    }
    @media(min-width:768px) {
        .tile {
            margin-bottom: 30px;
        }
        .circle-tile {
            margin-bottom: 30px;
        }
    }
    /*icon user*/
    .dark-blue {
        background-color: #34495e;
    }
    /*icon menoy*/
    .green {
        background-color: #16a085;
    }
    .blue {
        background-color: #2980b9;
    }
    .orange {
        background-color: #f39c12;
    }
    .red {
        background-color: #e74c3c;
    }
    .purple {
        background-color: #8e44ad;
    }
</style>