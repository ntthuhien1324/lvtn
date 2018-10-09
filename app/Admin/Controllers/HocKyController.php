<?php

namespace App\Admin\Controllers;

use App\Models\HocKy;
use App\Http\Controllers\Controller;
use App\Models\NamHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Grid;
use App\Admin\Extensions\Show;

class HocKyController extends Controller
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
            ->header('Học kỳ')
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
        $tenHocKy = HocKy::find($id)->ten;
        return $content
            ->header('Học kỳ')
            ->description($tenHocKy)
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
        $tenHocKy = HocKy::find($id)->ten;
        return $content
            ->header('Edit')
            ->description($tenHocKy)
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
            ->description('Học kỳ')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new HocKy);

        $grid->id('ID');
        $grid->ten('Tên học kỳ');
        $grid->id_nam_hoc('Tên năm học')->display(function () {
            return $this->namHoc->ten;
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
        $show = new Show(HocKy::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên học kỳ');
        $show->id_nam_hoc('Tên năm học');
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
        $form = new Form(new HocKy);

        $form->hidden('id','ID');
        $form->text('ten','Tên học kỳ')->rules('required');
        $form->select('id_nam_hoc','Tên năm học')->options(NamHoc::all()->pluck('ten','id'))
            ->rules('required');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
