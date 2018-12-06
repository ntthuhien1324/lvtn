<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/12/2018
 * Time: 8:01 SA
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Form;
use App\Admin\Extensions\Grid;
use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\KetQuaDangKy;
use App\Models\Lop;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\PhongHoc;
use App\Models\SinhVien;
use App\Models\ThoiGianHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Form\Tools;
use Encore\Admin\Layout\Content;

class QuanLyDiemController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Giảng viên')
            ->description('Quản lí điểm')
            ->body(
                view('vendor.details',
                    [
                        'template_body_name' => 'vendor.admin.giang_vien.quan_ly_diem.info',
                        'formDotDangKy' => $this->formDotDangKy(),
                        'gridLopHocPhan' => $this->gridLopHocPhan()->render()
                    ]
                )
            );
    }

    protected function formDotDangKy()
    {
        $form = new Form(new DotDangKy());
        $id = Admin::user()->id;
        $arrIdDotDangKy = LopHocPhan::where('id_gv',$id)->distinct()->pluck('id_dot_dang_ky')->toArray();
        $options = DotDangKy::whereIn('id',$arrIdDotDangKy)->orderBy('id', 'DESC')->pluck('ten', 'id')->toArray();
        $form->select('id_dot_dang_ky', 'Thời gian')->options($options)->attribute(['id' => 'dotDangKy']);
        $form->footer(function ($footer) {
            // disable reset btn
            $footer->disableReset();

            // disable `View` checkbox
            $footer->disableViewCheck();

            // disable `Continue editing` checkbox
            $footer->disableEditingCheck();

            $footer->disableSubmit();

        });
        $form->tools(function (Tools $tools) {
            $tools->disableList();
            $tools->disableView();
            $tools->disableDelete();
        });
//        return $form;
    }

    protected function gridLopHocPhan()
    {
        $grid = new Grid(new LopHocPhan());
        $user = Admin::user();
        $idUser = $user->id;
        $lopHocPhan = LopHocPhan::where('id_gv',$idUser)->orderByDesc('id_dot_dang_ky')->first();
        if (!empty($lopHocPhan)) {
            $grid->model()->where('id_dot_dang_ky',$lopHocPhan->id_dot_dang_ky)->where('id_gv',$idUser);
        } else {
            $grid->model()->where('id','-1');
        }
        $grid->id('Mã học phần')->display(function ($id) {
            return '<a href="/admin/giang-vien/quan-ly-diem/'.$id.'">'. $id .'</a>';
        });
        $grid->id_mon_hoc('Môn học')->display(function ($idMonHoc) {
            return "<span class='label label-success'>" . MonHoc::find($idMonHoc)->ten . "</span>";
        });
        $grid->column('Phòng')->display(function () {
            $idPhong = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('id_phong_hoc')->toArray();
            $phong = PhongHoc::whereIn('id',$idPhong)->pluck('ten')->toArray();
            $phong = array_map(function ($phong) {
                return "<span class='label label-success'>{$phong}</span>";
            }, $phong);
            return join('&nbsp;',$phong);
        });
        $grid->column('Buổi học')->display(function () {
            $day = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('ngay')->toArray();
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
            $timeStart = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('gio_bat_dau')->toArray();
            $timeEnd = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('gio_ket_thuc')->toArray();
            $time = array_map(function ($timeStart, $timeEnd) {
                return "<span class='label label-success'>{$timeStart} - {$timeEnd}</span>";
            }, $timeStart, $timeEnd);
            return join('&nbsp;', $time);
        });
        $grid->id_gv('Giảng viên')->display(function ($idGv) {
            return Administrator::find($idGv)->name;
        });
        $grid->sl_hien_tai('Số lượng hiện tại');
        $grid->ngay_bat_dau('Ngày bắt đầu');
        $grid->ngay_ket_thuc('Ngày kết thúc');
//        $grid->id_dot_dang_ky('Đợt đăng ký')->display(function ($idDotDangKy) {
//            if($idDotDangKy % 2 == 0) {
//                return "<span class='label label-info'>". DotDangKy::find($idDotDangKy)->ten ."</span>";
//            } else {
//                return "<span class='label label-success'>". DotDangKy::find($idDotDangKy)->ten ."</span>";
//            }
//
//        });
        $grid->created_at('Thời gian tạo');
        $grid->updated_at('Thời gian cập nhật');

        return $grid;
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $lopHocPhan = LopHocPhan::find($id);
        return $content
            ->header('Lớp HP')
            ->description($lopHocPhan->id)
            ->body($this->detail($id));
    }

    protected function detail($id) {
        $formLopHocPhan = $this->formLopHocPhan()->edit($id);
        $gridSVMonHoc = $this->gridSVMonHoc($id)->render();
        return view('vendor.details',
            [
                'template_body_name' => 'vendor.admin.giang_vien.sinh_vien_lop_hoc_phan.info',
                'formLopHocPhan' => $formLopHocPhan,
                'gridSVMonHoc' => $gridSVMonHoc
            ]
        );
    }

    protected function formLopHocPhan() {
        $form = new Form(new LopHocPhan());

        $form->text('id', 'Mã học phần');
        $form->select('id_mon_hoc', 'Môn học')->options(MonHoc::all()->pluck('ten', 'id'))->rules('required')->readOnly();
        $form->select('id_gv', 'Giảng viên')->options(Administrator::where('kieu_nguoi_dung', '0')->pluck('name', 'id'))->rules('required')->readOnly();
        $form->date('ngay_bat_dau', 'Ngày bắt đầu')->placeholder('Ngày bắt đầu')->rules('required')->readOnly();
        $form->date('ngay_ket_thuc', 'Ngày kết thúc')->placeholder('Ngày kết thúc')->rules('required')->readOnly();
        $form->hasMany('thoi_gian_hoc', 'Thời gian học', function (NestedForm $form) {
            $options = ['2' => 'Thứ 2', '3' => 'Thứ 3', '4' => 'Thứ 4', '5' => 'Thứ 5', '6' => 'Thứ 6', '7' => 'Thứ 7', '8' => 'Chủ nhật'];
            $form->select('ngay', 'Buổi học')->options($options)->readOnly();
            $form->select('id_phong_hoc', 'Phòng học')->options(PhongHoc::all()->pluck('ten', 'id'))->rules('required')->readOnly();
            $form->time('gio_bat_dau', 'Giờ học bắt đầu');
            $form->time('gio_ket_thuc', 'Giờ học kết thúc');
        })->rules('required');
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableReset();
            $footer->disableSubmit();
        });
        $form->tools(function (Tools $tools) {
            $tools->disableList();
            $tools->disableView();
            $tools->disableDelete();
        });

        return $form;
    }

    protected function gridSVMonHoc($id) {
        $grid = new Grid(new KetQuaDangKy());
        $grid->resource('/admin/giang-vien/diem');

        $user = Admin::user();
        $idUser = $user->id;
        $grid->model()->where('id_hoc_phan_dang_ky',$id);
        $grid->id_sv('MSSV')->display(function ($id) {
            $sinhVien = SinhVien::find($id);
            $mssv = $sinhVien->mssv ?: '';
            return $mssv;
        });
        $grid->column('Họ và tên')->display(function () {
            $sinhVien = SinhVien::find($this->id_sv);
            $hoTen = $sinhVien->ho_ten ?: '';
            return $hoTen;
        });
        $grid->id_hoc_phan_dang_ky('Mã HP')->display(function ($id) {
            $lopHP = LopHocPhan::find($id);
            $maHP = $lopHP->id ?: '';
            return $maHP;
        });
        $grid->id_mon_hoc('Môn học')->display(function ($id) {
            $monHoc = MonHoc::find($id);
            $tenMonHoc = $monHoc->ten ?: '';
            return $tenMonHoc;
        });
        $grid->column('Lớp')->display(function () {
            $idLop = SinhVien::find($this->id_sv)->id_lop;
            $tenLop = Lop::find($idLop)->ten;
            return "<span class='label label-info'>{$tenLop}</span>";
        });

        $idDotDangKy = LopHocPhan::find($id)->id_dot_dang_ky;
        $dotDangKy = DotDangKy::where('id', $idDotDangKy)->first();
        $statusEditDiem = $dotDangKy->trang_thai_sua_diem;
        if($statusEditDiem == null || $statusEditDiem == []) {
            $grid->diem_giua_ky('Điểm giữa kì')->sortable();
            $grid->diem_cuoi_ky('Điểm cuối kì')->sortable();
            $grid->column('Điểm tổng kết')->display(function () {
                if(!$this->diem_giua_ky || !$this->diem_cuoi_ky) {
                    return 'X';
                }
//                    }
                else {
                    return (
                            ($this->diem_giua_ky * $this->tl_diem_giua_ky) +
                            ($this->diem_cuoi_ky * $this->tl_diem_cuoi_ky)) / 100;
                }

            })->setAttributes(['class'=>'finalPoint']);

        } else {
            switch (true) {
                case in_array('1', $statusEditDiem) && in_array('2', $statusEditDiem):
                    $grid->diem_giua_ky('Điểm giữa kì')->editable()->sortable();
                    $grid->diem_cuoi_ky('Điểm cuối kì')->editable()->sortable();
                    break;
                case in_array('1', $statusEditDiem):
                    $grid->diem_giua_ky('Điểm giữa kì')->editable()->sortable();
                    $grid->diem_cuoi_ky('Điểm cuối kì')->sortable();
                    break;
                case in_array('2', $statusEditDiem):
                    $grid->diem_giua_ky('Điểm giữa kì')->sortable();
                    $grid->diem_cuoi_ky('Điểm cuối kì')->editable()->sortable();
                    break;
            }
            $grid->final('Điểm tổng kết')->display(function () {
                if(!is_numeric($this->diem_giua_ky ) || !is_numeric($this->diem_cuoi_ky ) ) {
                    return 'X';
                } else {
                    $script = <<<SCRIPT
                    $(document).ready ( function () {
                        $(document).on ("click", ".editable-submit", function () {
                         setTimeout(function(){ 
                           location.reload();
                         }, 1500);
                        });
                    });
SCRIPT;
                    Admin::script($script);
                    return (
                            ($this->diem_giua_ky * $this->tl_diem_giua_ky) +
                            ($this->diem_cuoi_ky * $this->tl_diem_cuoi_ky)) / 100;
                }
            })->setAttributes(['class'=>'finalPoint']);
        }
        $grid->updated_at('Thời gian cập nhật');

        return $grid;
    }
}