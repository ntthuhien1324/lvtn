<?php

namespace App\Admin\Controllers;

use App\Models\Lop;
use App\Models\SinhVien;
use App\Http\Controllers\Controller;
use App\Models\TrangThaiSinhVien;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SinhVienController extends Controller
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
            ->header('Sinh viên')
            ->description('Danh sách')
            ->body($this->grid());
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
        return $content
            ->header('Sinh viên')
            ->description(SinhVien::find($id)->ten)
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Sửa')
            ->description(SinhVien::find($id)->ten)
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Thêm')
            ->description('Sinh viên')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SinhVien);
        $grid->model()->orderByDesc('created_at');

//        $grid->id('ID');
        $grid->mssv('Mã số sinh viên');
        $grid->ho_ten('Họ và tên')->display(function ($hoTen) {
            return '<a href="/admin/sinh-vien/'. $this->id.'">'. $hoTen .'</a>';
        });
        $grid->ngay_sinh('Ngày sinh');
        $grid->email('Email');
        $grid->id_lop('Lớp')->display(function ($idLop) {
            return '<a href="/admin/lop/'. $idLop.'">'. Lop::find($idLop)->ten .'</a>';
        });
        $grid->nam_nhap_hoc('Năm nhập học');
        $grid->id_trang_thai('Trạng thái sinh viên')->display(function ($idTrangThai) {
            return TrangThaiSinhVien::find($idTrangThai)->trang_thai;
        });
        $grid->created_at('Thời gian tạo');
        $grid->updated_at('Thời gian cập nhật');

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
        $show = new Show(SinhVien::findOrFail($id));

        $show->mssv('Mã số sinh viên');
        $show->ho_ten('Họ và tên');
        $show->ngay_sinh('Ngày sinh');
        $show->email('Email');
        $show->id_lop('Lớp')->as(function ($idLop) {
            return Lop::find($idLop)->ten;
        });
        $show->nam_nhap_hoc('Năm nhập học');
        $show->id_trang_thai('Trạng thái sinh viên')->as(function ($idTrangThai) {
            return TrangThaiSinhVien::find($idTrangThai)->trang_thai;
        });
        $show->created_at('Thời gian tạo');
        $show->updated_at('Thời gian cập nhật');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SinhVien);

        $form->hidden('id','ID');
        $form->text('ho_ten','Họ và tên')->rules('required');
        $form->date('ngay_sinh','Ngày sinh')->format('DD/MM/YYYY')->rules('required');
        $form->text('mssv','Mã số sinh viên')->rules(function ($form) {
            return 'required|min:10|unique:sinh_vien,mssv,'.$form->model()->id.',id';
        });
        $form->email('Email');
        $form->hidden('password');
        $form->saving(function (Form $form) {
            $form->password = $form->ngay_sinh;
        });
        $form->select('id_lop')->options(Lop::all()->pluck('ten','id'))->rules('required');
        $form->year('nam_nhap_hoc','Năm nhập học')->rules('required');
        $form->select('id_trang_thai','Trạng thái')->options(TrangThaiSinhVien::all()->pluck('trang_thai','id'))->default(1);

        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
