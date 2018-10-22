<?php

namespace App\Admin\Controllers;

use App\Models\MonHoc;
use App\Models\MonHocSongSong;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class MonHocSongSongController extends Controller
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
            ->header('Môn học song song')
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
            ->header('Thêm môn học song song')
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
        $grid = new Grid(new MonHocSongSong);

        $grid->id('ID');
        $grid->id_mon_hoc_1('Môn học 1')
            ->display(function ($idMonHoc) {
                $tenMonHoc = MonHoc::find($idMonHoc)->ten;
                return '<a href="/admin/monhoc/' . $idMonHoc . '">'.$tenMonHoc.'</a>';
            });
        $grid->id_mon_hoc_2('Môn học 2')
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
        $show = new Show(MonHocSongSong::findOrFail($id));

        $show->id('ID');
        $show->id_mon_hoc_1('Môn học 1')
            ->as(function ($idMonHoc) {
                $tenMonHoc = MonHoc::find($idMonHoc)->ten;
                return $tenMonHoc;
            });
        $show->id_mon_hoc_2('Môn học 2')
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
        $form = new Form(new MonHocSongSong);

        $form->hidden('id','ID');
        $monHoc = MonHoc::all()->sortByDesc('ten')->pluck('ten','id');
        $form->select('id_mon_hoc_1', 'Môn học 1')->options($monHoc)->rules('required');
        $form->select('id_mon_hoc_2', 'Môn học 2')->options($monHoc)->rules('required');
        $form->saving(function (Form $form){
            if($form->id_mon_hoc_1 == $form->id_mon_hoc_2 ) {
                $error = new MessageBag([
                    'title'   => 'Lỗi',
                    'message' => 'Môn học 1 và Môn học 2 không được giống nhau',
                ]);
                return back()->with(compact('error'));
            }
        });
        $form->hidden('Created at');
        $form->hidden('Updated at');

        return $form;
    }
}