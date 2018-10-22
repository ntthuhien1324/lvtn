<?php

namespace App\Admin\Controllers;

use App\Models\NhomMonHoc;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class NhomMonHocController extends Controller
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
            ->header('Nhóm môn học')
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
        $tenNhomMonHoc = NhomMonHoc::find($id)->ten;
        return $content
            ->header('Chi tiết')
            ->description($tenNhomMonHoc)
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
        $tenNhomMonHoc = NhomMonHoc::find($id)->ten;
        return $content
            ->header('Sửa')
            ->description($tenNhomMonHoc)
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
            ->description('Nhóm môn học')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NhomMonHoc);

        $grid->id('ID');
        $grid->ten('Nhóm môn học');
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
        $show = new Show(NhomMonHoc::findOrFail($id));

        $show->id('ID');
        $show->ten('Nhóm môn học');
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
        $form = new Form(new NhomMonHoc);

        $form->hidden('id','ID');
        $form->text('ten','Tên nhóm môn học');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
