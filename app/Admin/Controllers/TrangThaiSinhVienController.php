<?php

namespace App\Admin\Controllers;

use App\Models\TrangThaiSinhVien;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\Extensions\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TrangThaiSinhVienController extends Controller
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
            ->header('Trạng thái sinh viên')
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
    public function edit($id, Content $content)
    {
        return $content
            ->header('Sửa')
            ->description('')
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
            ->header('Thêm trạng thái')
            ->description('')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TrangThaiSinhVien);

        $grid->id('ID');
        $grid->trang_thai('Trạng thái');
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
        $show = new Show(TrangThaiSinhVien::findOrFail($id));

        $show->id('ID');
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
        $form = new Form(new TrangThaiSinhVien);

//        $form->hidden('id','ID');
        $form->text('id', 'ID')->help('Chú ý: Nếu ID lớn hơn 5 thì sinh viên không được phép đăng ký')
            ->rules('required|unique:trang_thai_sinh_vien');
        $form->text('trang_thai', 'Tên trạng thái')->rules('required');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
