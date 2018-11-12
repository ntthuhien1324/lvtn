<?php

namespace App\Admin\Controllers;

use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\HocKy;
use App\Models\LopHocPhan;
use App\Http\Controllers\Controller;
use App\Models\MonHoc;
use App\Models\TietHoc;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\Extensions\Form;
use App\Admin\Extensions\Grid;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class LopHocPhanController extends Controller
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
            ->header('Lớp học phần')
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
            ->header('Thêm lớp học phần')
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
        $grid = new Grid(new LopHocPhan);
        $grid->model()->orderByDesc('created_at');

        $grid->id('Mã học phần')->display(function ($id) {
            return '<a href="/admin/lop-hoc-phan/'.$id.'">'. LopHocPhan::find($id)->ten .'</a>';
        });
        $grid->id_mon_hoc('Môn học')->display(function ($idMonHoc) {
            return "<span class='label label-success'>" . MonHoc::find($idMonHoc)->ten . "</span>";
        });
        $grid->id_gv('Giảng viên')->display(function ($idGv) {
            return Administrator::find($idGv)->name;
        });
        $grid->sl_hien_tai('Số lượng hiện tại');
        $grid->ngay_bat_dau('Ngày bắt đầu');
        $grid->ngay_ket_thuc('Ngày kết thúc');
        $grid->id_dot_dang_ky('Đợt đăng ký')->display(function ($idDotDangKy) {
                if($idDotDangKy % 2 == 0) {
                    return "<span class='label label-info'>". DotDangKy::find($idDotDangKy)->ten ."</span>";
                } else {
                    return "<span class='label label-success'>". DotDangKy::find($idDotDangKy)->ten ."</span>";
                }

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
        $show = new Show(LopHocPhan::findOrFail($id));

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
        $form = new Form(new LopHocPhan);

        $currentPath = Route::getFacadeRoot()->current()->uri();
        if($currentPath == 'admin/lop-hoc-phan/{lop_hoc_phan}/edit'){
            $form->display('id', 'Mã học phần');
        } else {
            $form->text('id', 'Mã học phần')->rules(function ($form){
                if (!$id = $form->model()->id) {
                    return 'required|unique:mon_hoc,id';
                }
            });
        }
        $form->select('id_dot_dang_ky', 'Đợt đăng ký')->options(DotDangKy::orderByDesc('created_at')
            ->pluck('ten', 'id'))->rules('required')->load('id_mon_hoc','/admin/lop-hoc-phan/mon-hoc');
        $form->select('id_subjects', 'Môn học')->options(MonHoc::all()->pluck('ten', 'id'))->rules('required');
        $form->select('id_user_teacher', 'Giảng viên')->options(Administrator::where('kieu_nguoi_dung', 0)
            ->pluck('name', 'id'))->rules('required');
        $form->hidden('sl_hien_tai', 'Số lượng hiện tại')->value('0');
        $form->number('sl_min', 'Số lượng tối thiểu')->rules('integer|min:5');
        $form->number('sl_max', 'Số lượng tối đa')->rules('integer|min:10');
        $form->date('ngay_bat_dau', 'Ngày bắt đầu')->rules('required');
        $form->date('ngay_ket_thuc', 'Ngày kết thúc')->rules('required');
        $form->hidden('Created at');
        $form->hidden('Updated at');

        $form->hasMany('thoiGianHoc', 'Thời gian học', function (NestedForm $form) {
            $options = ['2'=>'Thứ 2', '3'=>'Thứ 3', '4'=>'Thứ 4', '5'=>'Thứ 5', '6'=>'Thứ 6', '7'=>'Thứ 7', '8'=>'Chủ nhật'];
            $form->select('ngay', 'Ngày học')->options($options);
            $form->select('id_phong_hoc', 'Phòng học')->options(PhongHoc::all()->pluck('ten', 'id'));
            $timeStart = TietHoc::all()->pluck('gio_bat_dau', 'gio_bat_dau' );
            $timeEnd = TietHoc::all()->pluck('gio_ket_thuc', 'gio_ket_thuc' );



            //
            $form->select('time_study_start', 'Giờ học bắt đầu')->options($timeStart)->readOnly();
            $form->select('time_study_end', 'Giờ học kết thúc')->options($timeEnd)->readOnly();
        })->rules('required');
        $form->disableReset();
        $form->tools(function (Form\Tools $tools) use ($id) {
            $tools->add('<a href="/admin/subject_register/'.$id.'/edit" class="btn btn-sm btn-default" style="margin-right: 10px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Sửa</a>');
        });

        return $form;
    }

    public function monHoc(Request $request) {
        $idDotDangKy = $request->get('q');
        $dotDangKy = DotDangKy::find($idDotDangKy);
        $hocKy = $dotDangKy->hoc_ky;
        $hocKys = HocKy::where('ten', $hocKy)->get()->toArray();
        $monHocs = [];
        foreach($hocKys as $hocKy) {
            $hocKy = HocKy::find($hocKy['id']);
            array_push($monHocs, $hocKy->monHoc()->get(['id', DB::raw('ten as text')])) ;
        }
        return $monHocs;
    }
}
