<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    //regionQuản lý đợt đăng ký
    $router->resource('dot-dang-ky',DotDangKyController::class);

        //ajax
        $router->get('lop-hoc-phan/mon-hoc','LopHocPhanController@monHoc');
    $router->resource('lop-hoc-phan',LopHocPhanController::class);
    $router->resource('phong-hoc',PhongHocController::class);
    //endregion

    //region Quản lý môn học
    $router->resource('mon-hoc',MonHocController::class);
    $router->resource('nhom-mon-hoc',NhomMonHocController::class);
    $router->resource('ti-le-diem',TiLeDiemController::class);
    $router->resource('mon-hoc-song-song',MonHocSongSongController::class);
    $router->resource('mon-hoc-truoc-sau',MonHocTruocSauController::class);
    //endregion

    //regionQL Đăng ký học phần
    $router->resource('tiet-hoc',TietHocController::class);
    //endregion

    //region Quản lý Năm, học kỳ
    $router->resource('nam-hoc',NamHocController::class);
    $router->resource('hoc-ky',HocKyController::class);
    //endregion

    //regionAdmin
    $router->resource('user_gv',UserController::class);
    $router->resource('user_admin',UserController::class);
    //endregion

    //regionQuản lý Khoa, lớp
    $router->resource('lop',LopController::class);
    $router->resource('khoa',KhoaController::class);
    //endregion

    //regionQuản lý SV
    $router->resource('trang-thai-sinh-vien',TrangThaiSinhVienController::class);
    $router->resource('sinh-vien',SinhVienController::class);
    //endregion

    //thông báo
    $router->resource('thong-bao',ThongBaoController::class);

    //góp ý
    $router->resource('gop-y',GopYController::class);

    //regionRoute Giảng viên
    $router->resource('giang-vien/lop',GiangVienController::class);
    //thông tin lớp học phần
    $router->get('giang-vien/thong-tin-sinh-vien/{id}','ThongTinSinhVienController@show');
    $router->get('giang-vien/lop-hoc-phan','GiangVienController@lopHocPhan');
    $router->get('giang-vien','GiangVienController@lopHocPhan');
    //quản lý điểm
    $router->resource('giang-vien/diem',DiemController::class);
    $router->resource('giang-vien/quan-ly-diem',QuanLyDiemController::class);
    $router->get('giang-vien/diem-sinh-vien/{id}','ThongTinSinhVienController@chiTietDiem');
    //endregion

});
