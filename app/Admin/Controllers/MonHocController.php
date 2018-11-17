<?php

namespace App\Admin\Controllers;

use App\Models\MonHoc;
use App\Http\Controllers\Controller;
use App\Models\NhomMonHoc;
use App\Models\TiLeDiem;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\Extensions\Form;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Grid;
use App\Admin\Extensions\Show;

class MonHocController extends Controller
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
            ->header('Môn học')
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
        $tenMonHoc = MonHoc::find($id)->ten;
        return $content
            ->header('Chi tiết')
            ->description($tenMonHoc)
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
        $tenMonHoc = MonHoc::find($id)->ten;
        return $content
            ->header('Sửa')
            ->description($tenMonHoc)
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
            ->description('môn học')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MonHoc);

        $grid->id('Mã môn học');
        $grid->ten('Tên môn học');
        $grid->so_tin_chi('Số tín chỉ');
        $grid->id_ti_le_diem('Tỉ lệ điểm')->display(function () {
            return $this->tiLeDiem->ten;
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
        $show = new Show(MonHoc::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên môn học');
        $show->so_tin_chi('Số tín chỉ');
        $show->id_ti_le_diem('Tỉ lệ điểm')->as(function () {
            return $this->tiLeDiem->ten;
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
        $form = new Form(new MonHoc);

        $form->text('id','Mã môn học');
        $form->text('ten','Tên môn học')->rules('required');
        $form->number('so_tin_chi','Số tín chỉ')->rules('numeric|min:1|max:8');
        $form->select('id_ti_le_diem','Tỉ lệ điểm')
            ->options(TiLeDiem::all()->pluck('ten','id'))
            ->rules('required');
        $form->multipleSelect('nhomMonHoc','Nhóm môn học')
            ->options(NhomMonHoc::all()->pluck('ten','id'))
            ->rules('required');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
