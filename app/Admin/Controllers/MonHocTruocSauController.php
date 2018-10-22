<?php

namespace App\Admin\Controllers;

use App\Models\MonHoc;
use App\Models\MonHocTruocSau;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class MonHocTruocSauController extends Controller
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
            ->header('Môn học trước sau')
            ->description('Danh sách\'')
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
//            ->description('description')
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
//            ->description('description')
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
            ->header('Thêm môn học trước - sau')
//            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MonHocTruocSau);

        $grid->id('ID');
        $grid->id_mon_hoc_truoc('Môn học trước')
            ->display(function ($idMonHoc) {
                $tenMonHoc = MonHoc::find($idMonHoc)->ten;
                return '<a href="/admin/monhoc/' . $idMonHoc . '">'.$tenMonHoc.'</a>';
            });
        $grid->id_mon_hoc_sau('Môn học sau')
            ->display(function ($idMonHoc) {
                $tenMonHoc = MonHoc::find($idMonHoc)->ten;
                return '<a href="/admin/monhoc/' . $idMonHoc . '">'.$tenMonHoc.'</a>';
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
        $show = new Show(MonHocTruocSau::findOrFail($id));

        $show->id('ID');
        $show->id_mon_hoc_truoc('Môn học trước')
            ->as(function ($idMonHoc) {
                $tenMonHoc = MonHoc::find($idMonHoc)->ten;
                return $tenMonHoc;
            });
        $show->id_mon_hoc_sau('Môn học sau')
            ->as(function ($idMonHoc) {
                $tenMonHoc = MonHoc::find($idMonHoc)->ten;
                return $tenMonHoc;
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
        $form = new Form(new MonHocTruocSau);

        $form->hidden('id','ID');
        $monHoc = MonHoc::all()->sortByDesc('ten')->pluck('ten','id');
        $form->select('id_mon_hoc_truoc','Môn học trước')->options($monHoc)->rules('required');
        $form->select('id_mon_hoc_sau','Môn học sau')->options($monHoc)->rules('required');
        $form->saving(function (Form $form) {
            if($form->id_mon_hoc_truoc == $form->id_mon_hoc_sau) {
                $error = new MessageBag([
                    'title'   => 'Lỗi',
                    'message' => 'Môn học trước và môn học sau không được giống nhau',
                ]);
                return back()->with(compact('error'));
            }
        });
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}
