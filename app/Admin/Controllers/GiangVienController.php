<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 19/11/2018
 * Time: 1:36 SA
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Grid;
use App\Http\Controllers\Controller;
use App\Models\DotDangKy;
use App\Models\Khoa;
use App\Models\Lop;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\PhongHoc;
use App\Models\SinhVien;
use App\Models\ThoiGianHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GiangVienController extends Controller
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
            ->description('Danh sách lớp')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $lop = Lop::findOrFail($id);
        return $content
            ->header('Lớp')
            ->description($lop->ten)
            ->body($this->detailsView($id));
    }

    public function detailsView($id)
    {
        $show = $this->detail($id);
        $gridSv = $this->gridSv($id)->render();
        return view('vendor.details',
            [
                'template_body_name' => 'vendor.admin.giang_vien.info',
                'form' => $show,
                'gridSv' => $gridSv
            ]
        );
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lop());

        $grid->model()->orderBy('created_at', 'DESC');
        $user = Admin::user();
        $idUser = $user->id;
        $grid->model()->where('id_gv', $idUser);
        $grid->ten('Tên lớp')->display(function ($ten) {
            return '<a href="/admin/giang-vien/lop/' . $this->id . '">' . $ten . '</a>';
        });
        $grid->id_khoa('Tên khoa')->display(function ($idKhoa) {
            return Khoa::find($idKhoa)->ten;
        });
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });
        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->like('ten', 'Tên');
            $filter->in('id_khoa', 'Tên khoa')->select(Khoa::all()->pluck('ten','id'));
        });
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableExport();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Lop::findOrFail($id));

        $show->ten('Tên lớp');
        $show->id_khoa('Tên khoa')->as(function ($idKhoa) {
            return Khoa::find($idKhoa)->ten;
        });
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });;

        return $show;
    }

    protected function gridSv($idLop)
    {
        $grid = new Grid(new SinhVien());

        $grid->model()->where('id_lop', $idLop);
        $grid->mssv('Mã số sinh viên')->sortable();
        $grid->ho_ten('Họ và tên');
        $grid->ngay_sinh('Ngày sinh');
        $grid->email('Email');
        $grid->id_lop('Lớp')->display(function ($idLop) {
            if ($idLop) {
                return Lop::find($idLop)->ten;
            } else {
                return 'Không có';
            }
        });
        $grid->nam_nhap_hoc('Năm nhập học')->sortable();
        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->like('mssv', 'MSSV');
            $filter->like('ho_ten', 'Họ và tên');
            $filter->like('email', 'Email');
            $filter->equal('nam_nhap_hoc', 'Năm nhập học')->year();
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->append('<a href="/admin/giang-vien/thong-tin-sinh-vien/' . $actions->getKey() . '"><i class="fa fa-eye"></i> KQDK | </a>');
            $actions->append('<a href="/admin/giang-vien/diem-sinh-vien/' . $actions->getKey() . '"><i class="fa fa-search-plus"></i> Xem điểm</a>');

        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();

        return $grid;
    }

    public function lopHocPhan(Content $content)
    {
        return $content->header('Giảng viên')
            ->description('Xem lịch, TKB')
            ->body(
                view('vendor.details',
                    [
                        'template_body_name' => 'vendor.admin.giang_vien.lop_hoc_phan.info',
                        'formDotDangKy' => $this->formDotDangKy(),
                        'gridLopHocPhan' => $this->gridLopHocPhan()->render()
                    ]));
    }

    protected function formDotDangKy()
    {
        $form = new Form(new DotDangKy());
        $user = Admin::user();
        $idUser = $user->id;
        $idDotDangKy = LopHocPhan::where('id_gv', $idUser)->pluck('id_dot_dang_ky')->toArray();
        $form->select('id_dot_dang_ky', 'Đợt đăng ký')->options(DotDangKy::whereIn('id', $idDotDangKy)->orderByDesc('id')->pluck('ten', 'id'));
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

//        return $form;

    }

    protected function gridLopHocPhan()
    {
        $grid = new Grid(new LopHocPhan());
        $user = Admin::user();
        $idUser = $user->id;
        $lopHocPhan = LopHocPhan::where('id_gv', $idUser)->orderByDesc('id_dot_dang_ky')->first();
        if(!empty($lopHocPhan)) {
            $grid->model()->where('id_dot_dang_ky', $lopHocPhan->id_dot_dang_ky)->where('id_gv', $idUser);
        } else {
            $grid->model()->where('id', '-1');
        }
        $grid->id('Mã học phần')->display(function ($name) {
            return '<a href="/admin/giang-vien/quan-ly-diem/' . $this->id . '">' . $name . '</a>';
        });
        $grid->id_mon_hoc('Môn học')->display(function ($idMonHoc) {
            if ($idMonHoc) {
                $name = MonHoc::find($idMonHoc)->ten;
                return "<span class='label label-info'>{$name}</span>";
            } else {
                return '';
            }
        });
        $grid->column('Phòng')->display(function () {
            $idPhong = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('id_phong_hoc')->toArray();
            $phongHoc = PhongHoc::whereIn('id', $idPhong)->pluck('ten')->toArray();
            $phongHoc = array_map(function ($phongHoc) {
                return "<span class='label label-success'>{$phongHoc}</span>";
            }, $phongHoc);
            return join('&nbsp;', $phongHoc);
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
        $grid->sl_hien_tai('Số lượng hiện tại');

        $grid->ngay_bat_dau('Ngày bắt đầu');
        $grid->ngay_ket_thuc('Ngày kết thúc');
        //action
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });
        $grid->disableFilter();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();

        return $grid;
    }
    #endregion




}
