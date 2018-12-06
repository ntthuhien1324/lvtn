<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/12/2018
 * Time: 10:29 SA
 */


namespace App\Admin\Controllers;

use App\Admin\Extensions\Form;
use App\Http\Controllers\Controller;
use App\Models\KetQuaDangKy;
use Encore\Admin\Controllers\HasResourceActions;

class DiemController extends Controller
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
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new YourModel);

        $grid->id('ID')->sortable();
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(YourModel::findOrFail($id));

        $show->id('ID');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new KetQuaDangKy());

        $form->display('id', 'ID');
        $form->number('diem_giua_ky','Điểm giữa kỳ')->rules('numeric|min:0|max:10');
        $form->number('diem_cuoi_ky','Điểm cuối kỳ')->rules('numeric|min:0|max:10');
        $form->number('tl_diem_giua_ky', 'Tỉ lệ điểm giữa kì');
        $form->number('tl_diem_cuoi_ky', 'Tỉ lệ điểm cuối kì');
        $form->hidden('da_hoc');
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');
        $form->saving(function (Form $form){
            $form->da_hoc = 1;
        });

        return $form;
    }
}