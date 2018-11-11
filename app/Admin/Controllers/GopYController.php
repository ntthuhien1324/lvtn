<?php

namespace App\Admin\Controllers;

use App\Models\GopY;
use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GopYController extends Controller
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
            ->header('Góp ý kiến')
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
            ->header('Chi tiết')
            ->description('')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
//    public function edit($id, Content $content)
//    {
//        return $content
//            ->header('Sửa')
//            ->description('')
//            ->body($this->form()->edit($id));
//    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
//    public function create(Content $content)
//    {
//        return $content
//            ->header('Create')
//            ->description('description')
//            ->body($this->form());
//    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GopY);
        $grid->model()->orderByDesc('created_at');

        $grid->id('ID');
        $grid->tieu_de('Tiêu đề')->display(function ($tieuDe) {
            return '<a href="/admin/gop-y/'. $this->id .'">'. $tieuDe .'</a>';
        });
        $grid->id_user('MSSV')->display(function ($idUser) {
            return SinhVien::find($idUser)->mssv;
        });
        $grid->ho_ten('Họ tên')->display(function () {
            return SinhVien::find($this->id_user)->ho_ten;
        });
        $trangThai = [
            'on'  => ['value' => 1, 'text' => 'Đã xem', 'color' => 'default'],
            'off' => ['value' => 0, 'text' => 'Chưa xem', 'color' => 'primary'],
        ];
        $grid->trang_thai()->switch($trangThai);
        $grid->created_at('Thời gian tạo');
        $grid->updated_at('Thời gian cập nhật');

        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });
        $grid->disableCreateButton();
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
        $gopY = GopY::findOrFail($id);
        $show = new Show($gopY);
        $gopY->trang_thai = 1;
        $gopY->save();

        $show->id('ID');
        $show->tieu_de('Tiêu đề');
        $show->noi_dung('Nội dung');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GopY);

        $form->display('ID');
        $trangThai = [
            'on'  => ['value' => 1, 'text' => 'Đã xem', 'color' => 'default'],
            'off' => ['value' => 0, 'text' => 'Chưa xem', 'color' => 'primary'],
        ];
        $form->switch('trang_thai','Trạng thái')->states($trangThai);
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }
}
