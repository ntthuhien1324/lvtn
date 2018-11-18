<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 19/11/2018
 * Time: 2:56 SA
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Grid;
use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\KetQuaDangKy;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\PhongHoc;
use App\Models\SinhVien;
use App\Models\ThoiGianHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;

class ThongTinSinhVienController extends Controller
{
    use HasResourceActions;

    public function show($id, Content $content)
    {
        $sinhVien = SinhVien::findOrFail($id);
        return $content
            ->header('Thông tin sinh viên')
            ->description($sinhVien->ho_ten)
            ->body(
                view('vendor.details',
                    [
                        'template_body_name' => 'vendor.admin.giang_vien.ket_qua_dang_ky_sinh_vien.info',
                        'form' => $this->formDotDangKy($id),
                        'grid' => $this->grid($id)->render(),
                        'idUser' => $id
                    ])
            );
    }

    protected function formDotDangKy($id) {
        $form = new Form(new DotDangKy());

        $arrIdDotDangKy = KetQuaDangKy::where('id_sv',$id)->distinct()->pluck('id_dot_dang_ky')->toArray();
        $options = DotDangKy::whereIn('id',$arrIdDotDangKy)->orderBy('id', 'DESC')->pluck('ten', 'id')->toArray();
        $form->select('id_dot_dang_ky', 'Đợt đăng ký')->options($options)->attribute(['id' => 'ketQuaDangKySinhVien']);
        $form->footer(function ($footer) {

            // disable reset btn
            $footer->disableReset();

            // disable submit btn
            $footer->disableSubmit();

            // disable `View` checkbox
            $footer->disableViewCheck();

            // disable `Continue editing` checkbox
            $footer->disableEditingCheck();

        });

        return $form;
    }

    protected function grid($id) {
        $grid = new Grid(new KetQuaDangKy());

        $idDotDangKy = KetQuaDangKy::where('id_sv', $id)->orderBy('id_dot_dang_ky', 'DESC')->first()->id_dot_dang_ky;
        $grid->model()->where('id_dot_dang_ky', $idDotDangKy)->where('id_sv', $id);
        $grid->column('Mã học phần')->display(function () {
            $lopHocPhan = LopHocPhan::where('id',$this->id_hoc_phan_dang_ky)->first();
            if (!empty($lopHocPhan)) {
                return $lopHocPhan->id;
            } else {
                return '';
            }
        });
        $grid->id_mon_hoc('Môn học')->display(function () {
            $idMonHoc = $this->id_mon_hoc;
            if (!empty($idMonHoc)) {
                return MonHoc::find($idMonHoc)->ten;
            } else {
                return '';
            }
        });
        $grid->column('Phòng')->display(function () {
            $idPhong = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('id_phong_hoc')->toArray();
            $phongHoc = PhongHoc::whereIn('id', $idPhong)->pluck('ten')->toArray();
            $phongHoc = array_map(function ($phongHoc) {
                return "<span class='label label-success'>{$phongHoc}</span>";
            }, $phongHoc);
            return join('&nbsp;', $phongHoc);
        });
        $grid->column('Buổi học')->display(function () {
            $day = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('ngay')->toArray();
            $day = array_map(function ($day) {
                switch ($day) {
                    case 2:
                        $day = 'Thứ 2';
                        break;
                    case 3:
                        $day = 'Thứ 3';
                        break;
                    case 4:
                        $day = 'Thứ 4';
                        break;
                    case 5:
                        $day = 'Thứ 5';
                        break;
                    case 6:
                        $day = 'Thứ 6';
                        break;
                    case 7:
                        $day = 'Thứ 7';
                        break;
                    case 8:
                        $day = 'Chủ nhật';
                        break;
                }

                return "<span class='label label-success'>{$day}</span>";
            }, $day);
            return join('&nbsp;', $day);
        });
        $grid->column('Thời gian học')->display(function () {
            $timeStart = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('gio_bat_dau')->toArray();
            $timeEnd = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('gio_ket_thuc')->toArray();
            $time = array_map(function ($timeStart, $timeEnd) {
                return "<span class='label label-success'>{$timeStart} - {$timeEnd}</span>";
            }, $timeStart, $timeEnd);
            return join('&nbsp;', $time);
        });
        $grid->column('Giảng viên')->display(function () {
            $lopHocPhan = LopHocPhan::where('id',$this->id_hoc_phan_dang_ky)->first();
            if (!empty($subjectRegister)) {
                $gv = Administrator::find($lopHocPhan->id_gv);
                if ($gv) {
                    return $gv->ten;
                } else {
                    return '';
                }
            } else {
                return '';
            }
        });
        $grid->column('Ngày bắt đầu')->display(function (){
            $idLopHocPhan = $this->id_hoc_phan_dang_ky;
            $lopHocPhan = LopHocPhan::find($idLopHocPhan);
            if($lopHocPhan->ngay_bat_dau){
                return $lopHocPhan->ngay_bat_dau;
            } else {
                return '';
            }
        });
        $grid->column('Ngày kết thúc')->display(function (){
            $idLopHocPhan = $this->id_hoc_phan_dang_ky;
            $lopHocPhan = LopHocPhan::find($idLopHocPhan);
            if($lopHocPhan->ngay_ket_thuc){
                return $lopHocPhan->ngay_ket_thuc;
            } else {
                return '';
            }
        });
        $grid->column('Sô tín chỉ hiện tại')->display(function () use ($id, $idDotDangKy){
            $idMonHoc = KetQuaDangKy::where('id_sv', $id)->where('id_dot_dang_ky',  $idDotDangKy)->pluck('id_mon_hoc');
            $monHocs = MonHoc::find($idMonHoc);
            $sumTinChi = 0;
            foreach ($monHocs as $monHoc){
                $sumTinChi += $monHoc->so_tin_chi;
            }
            return $sumTinChi;

        });
        $grid->disableExport();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableActions();

        return $grid;
    }
}