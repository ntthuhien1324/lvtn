<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Routing\Router;

Route::get('/', function () {
    return view('welcome');
});
Route::get('getLogin', 'ThongBaoLoginController@list');
Route::post('postLogin','UserSinhVienController@postlogin');
Route::get('logout', 'UserSinhVienController@logout');
Route::group(['prefix'=>'user', 'middleware'=>'sinhVienLogin'], function(Router $router){

    //Trang chủ sau khi đăng nhập
    $router->resource('sinh-vien', UserSinhVienController::class);

    //Trang đăng ký môn học
    Route::group(['middleware' => ['dangKyMonHoc']], function (Router $router) {
        $router->resource('dang-ky-mon-hoc', DangKyMonHocController::class);
    });
});