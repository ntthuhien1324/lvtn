<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\KetQuaDangKy;
use App\Models\Lop;
use App\Models\LopHocPhan;
use App\Models\SinhVien;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $countUserSV = SinhVien::count();
        $countGV = Administrator::where('kieu_nguoi_dung',0)->count();
        $countUserQuanTri = Administrator::where('kieu_nguoi_dung',1)->count();
        $countLop = Lop::count();
        $countDotDangKy = DotDangKy::count();
        $countLopHocPhan = LopHocPhan::count();
        $namHoc = SinhVien::distinct('nam_nhap_hoc')->orderByDesc('nam_nhap_hoc')->limit(6)->pluck('nam_nhap_hoc')->toArray();
        $namHoc = array_map('intval',$namHoc);
        $countSV = [];
        foreach ($namHoc as $nam) {
            $svMoiNam = SinhVien::where('nam_nhap_hoc',$nam)->count();
            array_push($countSV,$svMoiNam);
        }
//        $dotDangKy = ['HK2 Năm học 2017 - 2018', 'HK hè Năm học 2017 - 2018','HK1 Năm học 2018 - 2019'];
        $dotDangKy = DotDangKy::orderBy('id','DESC')->limit(3)->pluck('id')->toArray();
        $dotDangKy2 = DotDangKy::orderBy('id','DESC')->limit(3)->pluck('ten')->toArray();
        $dataDotDangKy = [];
        foreach ($dotDangKy as $key) {
            $countSVDangKY = KetQuaDangKy::where('id_dot_dang_ky',$key)->count();
            array_push($dataDotDangKy,$countSVDangKY);
        }
//        $dataDotDangKy = [30,50,20];
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
                    'dotDangKy' => json_encode($dotDangKy2),
                    'dataDotDangKy' => json_encode($dataDotDangKy),

                ]
            )
        );
        return $content;
    }
}
