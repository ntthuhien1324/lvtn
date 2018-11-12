<?php

namespace App\Admin\Controllers;

use App\Models\DotDangKy;
use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Helpers\Helpers;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;
use Symfony\Component\Console\Helper\Helper;

class DotDangKyController extends Controller
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
            ->header('Đợt đăng ký')
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
            ->header('Detail')
            ->description('description')
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
            ->description(DotDangKy::find($id)->ten)
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
            ->header('Thêm Đợt đăng ký')
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
        $grid = new Grid(new DotDangKy);
        $grid->model()->orderByDesc('created_at');

        $grid->id('ID');
        $grid->ten('Tên đợt đăng ký')->display(function ($ten) {
            return '<a href="/admin/dot-dang-ky/'.$this->id.'">'. $ten .'</a>';
        });
        $grid->tg_bat_dau('Thời gian bắt đầu');
        $grid->tg_ket_thuc('Thời gian kết thúc');
        $grid->hoc_ky('Học kỳ')->display(function ($hocKy) {
            switch ($hocKy) {
                case 0: return "<span class='label label-info'>Học kỳ hè</span>";
                    break;
                case 1: return "<span class='label label-info'>Học kỳ 1</span>";
                    break;
                case 2: return "<span class='label label-info'>Học kỳ 2</span>";
                    break;
                default:
                    return '';
            }
        });
        $grid->tc_max('Số tín chỉ tối đa');
        $grid->tc_min('Số tín chỉ tối thiểu');
        $grid->trang_thai('Trạng thái')->display(function ($trangThai) {
            if($trangThai == 1){
                return "<span class='label label-success'>Đang mở</span>";
            } else {
                return "<span class='label label-danger'>Đang đóng</span>";
            }
        });

        $grid->created_at('Thời gian tạo');
        $grid->updated_at('Thời gian cập nhật');
        $grid->disableExport();

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
        $show = new Show(DotDangKy::findOrFail($id));

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
        $form = new Form(new DotDangKy);

        $form->display('id', 'ID');
        $form->text('ten', 'Tên')->rules('required')
            ->help('Vui lòng đặt tên là HK - Năm học (VD:HK2 - Năm 2018-2019 ) ');
        $form->datetimeRange('tg_bat_dau', 'tg_ket_thuc', 'Thời gian đăng ký')
            ->rules('required');
        $options = [0 => 'Học kỳ hè', 1 => 'Học kỳ 1', 2 => 'Học kỳ 2'];
        $form->select('hoc_ky', 'Học kỳ')->options($options);
        $states = [
            'on'  => ['value' => 1, 'text' => 'Mở', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Đóng', 'color' => 'danger'],
        ];
        $form->switch('trang_thai', 'Trạng thái')->states($states)->default('0');
        $options = ['All'=>'Tất cả', '1'=>'Giữa kì', '2'=>'Cuối kì' ];
        $options2 = ['AllPoint'=>'Tất cả','1'=>'Giữa kì', '2'=>'Cuối kì' ];

        $form->checkbox('trang_thai_import_diem', 'Trạng thái import')->options($options);
        $form->checkbox('trang_thai_sua_diem', 'Trạng thái sửa điểm')->options($options2);
        $script = <<<EOT
            $(function () {
                $('input[value="All"]').on('ifChecked', function(event){
                  $('input[name="trang_thai_import_diem[]"]').iCheck('check');
                });
                $('input[value="All"]').on('ifUnchecked', function(event){
                  $('input[name="trang_thai_import_diem[]"]').iCheck('uncheck');
                });
                 $('input[value="AllPoint"]').on('ifChecked', function(event){
                  $('input[name="trang_thai_sua_diem[]"]').iCheck('check');
                });
                $('input[value="AllPoint"]').on('ifUnchecked', function(event){
                  $('input[name="trang_thai_sua_diem[]"]').iCheck('uncheck');
                });
            });
EOT;
        Admin::script($script);
        $currentPath = Route::getFacadeRoot()->current()->uri();
        $form->saving(function (Form $form) use ($currentPath) {
            if($form->trang_thai_import_diem['0'] == 'All' || $form->trang_thai_import_diem['0'] == '1' && $form->trang_thai_import_diem['1'] == '2'){
                $form->trang_thai_import_diem = 'All';
            }
            if(in_array('AllPoint',$form->trang_thai_sua_diem)){
                $form->trang_thai_sua_diem = ["1","2"];
            }
            if($form->trang_thai == 'on' ) {
                if($currentPath != "admin/dot-dang-ky/{dot_dang_ky}/edit") {
                    $countStatusActive = DotDangKy::where('trang_thai', 1)->get()->count();
                } else {
                    $countStatusActive = DotDangKy::where('id', '!=', $form->model()->id)->where('trang_thai', 1)->get()->count();
                }
                if ($countStatusActive > 0) {
                    $error = new MessageBag([
                        'title' => 'Lỗi',
                        'message' => 'Có đợt đăng ký đang mở',
                    ]);
                    return back()->with(compact('error'));
                }
            }
//                }
        });
        if($currentPath == "admin/dot-dang-ky/{dot_dang_ky}/edit") {
            $form->number('tc_max', 'Số tín chỉ lớn nhất')->rules('integer|max:28');
            $form->number('tc_min', 'Số tín chỉ nhỏ nhất')->rules('integer|min:10');
        } else {
            $form->hidden('tc_min')->value(10);
            $form->hidden('tc_max')->value(28);
        }
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
