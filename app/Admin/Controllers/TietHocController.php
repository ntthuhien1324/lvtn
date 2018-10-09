<?php

namespace App\Admin\Controllers;

use App\Models\TietHoc;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Grid;
use App\Admin\Extensions\Show;

class TietHocController extends Controller
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
            ->header('Tiết học')
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
        $tenTietHoc = TietHoc::find($id)->ten;
        return $content
            ->header('Tiết học')
            ->description($tenTietHoc)
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
        $tenTietHoc = TietHoc::find($id)->ten;
        return $content
            ->header('Sửa')
            ->description($tenTietHoc)
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
            ->description('Tiết học')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TietHoc);

        $grid->id('ID');
        $grid->ten('Tên tiết học');
        $grid->gio_bat_dau('Giờ bắt đầu');
        $grid->gio_ket_thuc('Giờ kết thúc');
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
        $show = new Show(TietHoc::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên tiết học');
        $show->gio_bat_dau('Giờ bắt đầu');
        $show->gio_ket_thuc('Giờ kết thúc');
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
        $form = new Form(new TietHoc);

        $form->hidden('id','ID');
        $form->text('ten','Tên tiết học')->rules('required');
        $form->time('gio_bat_dau','Giờ bắt đầu')->format('HH:mm');
        $form->time('gio_ket_thuc','Giờ kết thúc')->format('HH:mm');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
