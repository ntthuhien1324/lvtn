<?php

namespace App\Admin\Controllers;

use App\Models\ThongBao;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Show;

class ThongBaoController extends Controller
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
            ->header('Thông báo')
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
        $tieuDe = ThongBao::find($id)->ten;
        return $content
            ->header('Thông báo')
            ->description($tieuDe)
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
        $tieuDe = ThongBao::find($id)->ten;
        return $content
            ->header('Sửa thông báo')
            ->description($tieuDe)
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
            ->header('Thêm thông báo')
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
        $grid = new Grid(new ThongBao);

        $grid->id('ID');
        $grid->ten('Tiêu đề')->display(function ($tieuDe) {
            return '<a href="thong-bao/'.$this->id.'">'.$tieuDe.'</a>';
        });
//        $grid->noi_dung('Nội dung thông báo');
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
        $show = new Show(ThongBao::findOrFail($id));

        $show->id('ID');
        $show->ten('Tiêu đề');
        $show->noi_dung('Nội dung thông báo');
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
        $form = new Form(new ThongBao);

        $form->hidden('id','ID');
        $form->text('ten','Tiêu đề')->rules('required');
        $form->textarea('noi_dung','Nội dung thông báo');
        $form->file('url','Upload file')->rules('mimes:pdf,docx,xlsx');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
