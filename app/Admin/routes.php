<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    //thông báo
    $router->resource('thongbao',ThongBaoController::class);

    //tiết học
    $router->resource('tiethoc',TietHocController::class);

    //năm học
    $router->resource('namhoc',NamHocController::class);

    //học kỳ
    $router->resource('hocky',HocKyController::class);

    //môn học
    $router->resource('monhoc',MonHocController::class);
    //nhóm môn học
    $router->resource('nhommonhoc',NhomMonHocController::class);
    //môn học song song
    $router->resource('monhocsongsong',MonHocSongSongController::class);
    //môn học trước sau
    $router->resource('monhoctruocsau',MonHocTruocSauController::class);

    //tỉ lệ điểm
    $router->resource('tilediem',TiLeDiemController::class);

    //user
    $router->resource('user_gv',UserController::class);
    $router->resource('user_admin',UserController::class);

    //lớp
    $router->resource('lop',LopController::class);
    //khoa
    $router->resource('khoa',KhoaController::class);

});
