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

    //tỉ lệ điểm
    $router->resource('tilediem',TiLeDiemController::class);

});
