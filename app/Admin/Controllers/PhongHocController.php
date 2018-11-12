<?php

namespace App\Admin\Controllers;

use App\Models\PhongHoc;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PhongHocController extends Controller
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
            ->header('Phòng học')
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
            ->header('Thêm phòng')
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
        $grid = new Grid(new PhongHoc);
        $grid->model()->orderByDesc('created_at');

        $grid->id('ID');
        $grid->ten('Tên phòng')->display(function ($ten){
            return  '<a href="/admin/phong-hoc/' . $this->id . '">'.$ten.'</a>';
        })->sortable();
        $grid->created_at('Thời gian tạo');
        $grid->updated_at('Thời gian cập nhật');
        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->in('ten', 'Tên phòng')->multipleSelect(PhongHoc::all()->pluck('ten','ten'));
            $filter->between('created_at', 'Tạo vào lúc')->datetime();
        });

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
        $show = new Show(PhongHoc::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên phòng');
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
        $form = new Form(new PhongHoc);

        $form->hidden('ID');
        $form->text('ten', 'Tên phòng')->rules(function ($form){
            return 'required|unique:phong_hoc,ten,'.$form->model()->id.',id';
        });
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
