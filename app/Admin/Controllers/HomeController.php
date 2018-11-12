<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $countUserSV = 0;
        $countGV = 0;
        $countUserQuanTri = 0;
        $countLop = 0;
        $countDotDangKy = 0;
        $countLopHocPhan = 0;
        $namHoc = [2014,2015,2016,2017,2018];
        $countSV = [2,3,4,5,6];
        $dotDangKy = ['HK2 Năm học 2017 - 2018', 'HK hè Năm học 2017 - 2018','HK1 Năm học 2018 - 2019'];
        $dataDotDangKy = [30,50,20];
        $content->header('Trang chủ');
        $content->row(
            view('vendor.admin.dashboard.home-dashboard',
                [
                    'countUserSV' => $countUserSV,
                    'countGV' => $countGV,
                    'countUserQuanTri' => $countUserQuanTri,
                    'countLop' => $countLop,
                    'countDotDangKy' => $countDotDangKy,
                    'countLopHocPhan' => $countLopHocPhan,
                    //chart 1
                    'arrNamHoc' => json_encode($namHoc),
                    'countSV' =>json_encode($countSV),
                    //chart 2
                    'dotDangKy' => json_encode($dotDangKy),
                    'dataDotDangKy' => json_encode($dataDotDangKy),

                ]
            )
        );
        return $content;
    }
}
