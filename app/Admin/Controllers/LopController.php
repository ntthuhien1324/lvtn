<?php

namespace App\Admin\Controllers;

use App\Models\Administrator;
use App\Models\Khoa;
use App\Models\Lop;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class LopController extends Controller
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
            ->header('Lớp')
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
            ->description(Lop::find($id)->ten)
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
            ->description(Lop::find($id)->ten)
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
            ->header('Thêm lớp')
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
        $grid = new Grid(new Lop);

        $grid->id('ID');
        $grid->ten('Tên lớp');
        $grid->id_khoa('Tên khoa')->display(function ($idKhoa) {
            return Khoa::find($idKhoa)->ten;
        });
        $grid->id_gv('Giảng viên chủ nhiệm')->display(function ($idGv) {
            $gv = Administrator::find($idGv);
            $tenGv = $gv ? $gv->name : '';
            return $tenGv;
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
        $show = new Show(Lop::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên lớp');
        $show->id_khoa('Tên khoa')->as(function ($idKhoa) {
            return Khoa::find($idKhoa)->ten;
        });
        $show->id_gv('Giảng viên chủ nhiệm')->as(function ($idGv) {
            $gv = Administrator::find($idGv);
            $tenGv = $gv ? $gv->name : '';
            return $tenGv;
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
        $form = new Form(new Lop);

        $form->hidden('id','ID');
        $form->text('ten', 'Tên lớp')->rules(function ($form){
            return 'required|unique:lop,ten,'.$form->model()->id.',id';
        });
        $form->select('id_khoa', 'Tên khoa')->options(Khoa::all()->pluck('ten', 'id'))->rules('required');
        $form->select('id_gv', 'Giảng viên chủ nhiệm')->options(Administrator::where('kieu_nguoi_dung', 0)->pluck('name', 'id'));
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
