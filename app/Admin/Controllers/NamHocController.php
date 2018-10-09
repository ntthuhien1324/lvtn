<?php

namespace App\Admin\Controllers;

use App\Models\NamHoc;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Grid;
use App\Admin\Extensions\Show;

class NamHocController extends Controller
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
            ->header('Năm học')
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
        $tenNamHoc = NamHoc::find($id)->ten;
        return $content
            ->header('Chi tiết')
            ->description($tenNamHoc)
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
        $tenNamHoc = NamHoc::find($id)->ten;
        return $content
            ->header('Sửa')
            ->description($tenNamHoc)
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
            ->description('Năm học')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NamHoc);

        $grid->id('ID');
        $grid->ten('Tên năm học');
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
        $show = new Show(NamHoc::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên năm học');
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
        $form = new Form(new NamHoc);

        $form->hidden('id','ID');
        $form->text('ten','Tên năm học')->rules('required')->help('VD: 2014 - 2015, 2015 - 2016,...');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
