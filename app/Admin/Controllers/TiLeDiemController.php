<?php

namespace App\Admin\Controllers;

use App\Models\TiLeDiem;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use App\Admin\Extensions\Grid;
use App\Admin\Extensions\Show;
use Illuminate\Support\MessageBag;

class TiLeDiemController extends Controller
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
            ->header('Tỉ lệ điểm')
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
        $tenTiLeDiem = TiLeDiem::find($id)->ten;
        return $content
            ->header('Tỉ lệ điểm')
            ->description($tenTiLeDiem)
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
        $tenTiLeDiem = TiLeDiem::find($id)->ten;
        return $content
            ->header('Sửa')
            ->description($tenTiLeDiem)
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
            ->description('Tỉ lệ điểm')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TiLeDiem);

        $grid->id('ID');
        $grid->ten('Tên tỉ lệ điểm');
        $grid->ti_le_giua_ky('Tỉ lệ giữa kỳ');
        $grid->ti_le_cuoi_ky('Tỉ lệ cuối kỳ');
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
        $show = new Show(TiLeDiem::findOrFail($id));

        $show->id('ID');
        $show->ten('Tên tỉ lệ điểm');
        $show->ti_le_giua_ky('Tỉ lệ giữa kỳ');
        $show->ti_le_cuoi_ky('Tỉ lệ cuối kỳ');
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
        $form = new Form(new TiLeDiem);

        $form->hidden('ID');
        $form->text('ten','Tên tỉ lệ điểm')->rules('required');
        $form->number('ti_le_giua_ky','Tỉ lệ giữa kỳ (%)')->rules('numeric|min:20|max:50');
        $form->number('ti_le_cuoi_ky','Tỉ lệ cuối kỳ (%)')->rules('numeric|min:50|max:80');
        $form->hidden('Created at');
        $form->hidden('Updated at');
        $form->saving(function (Form $form){
            if($form->ti_le_giua_ky + $form->ti_le_cuoi_ky != 100){
                $error = new MessageBag([
                    'title'   => 'Lỗi',
                    'message' => 'Tỉ lệ điểm không là 100%',
                ]);
                return back()->with(compact('error'));
            }
        });

        return $form;
    }
}
