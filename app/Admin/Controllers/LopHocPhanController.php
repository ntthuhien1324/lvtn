<?php

namespace App\Admin\Controllers;

use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\HocKy;
use App\Models\LopHocPhan;
use App\Http\Controllers\Controller;
use App\Models\MonHoc;
use App\Models\PhongHoc;
use App\Models\ThoiGianHoc;
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
use Illuminate\Support\MessageBag;

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
            return '<a href="/admin/lop-hoc-phan/'.$id.'">'. $id .'</a>';
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
        $show->id_dot_dang_ky('Đợt đăng ký')->as(function ($idDotDangKy) {
            return DotDangKy::find($idDotDangKy)->ten;
        });
        $show->id_mon_hoc('Môn học')->as(function ($idMonHoc) {
            return MonHoc::find($idMonHoc)->ten;
        });
        $show->id_gv('Giảng viên')->as(function ($idGv) {
            return Administrator::find($idGv)->name;
        });
        $show->sl_hien_tai('Số lượng hiện tại');
        $show->sl_min('Số lượng tối thiểu');
        $show->sl_max('Số lượng tối đa');
        $show->ngay_bat_dau('Ngày bắt đầu');
        $show->ngay_ket_thuc('Ngày kết thúc');
        $show->created_at('Thời gian tạo');
        $show->updated_at('Thời gian cập nhật');
        $show->divider();
        $show->thoi_gian_hoc('Thời gian học', function ($thoiGianHoc) {
            $thoiGianHoc->ngay('Ngày')->display(function ($ngay) {
                switch ($ngay) {
                    case '2': return 'Thứ 2'; break;
                    case '3': return 'Thứ 3'; break;
                    case '4': return 'Thứ 4'; break;
                    case '5': return 'Thứ 5'; break;
                    case '6': return 'Thứ 6'; break;
                    case '7': return 'Thứ 7'; break;
                    case '8': return 'Chủ nhật'; break;
                    default: return '';
                }
            });
            $thoiGianHoc->id_phong_hoc('Phòng học')->display(function ($idPhong) {
                return PhongHoc::find($idPhong)->ten;
            });
            $thoiGianHoc->gio_bat_dau('Giờ học bắt đầu');
            $thoiGianHoc->gio_ket_thuc('Giờ học kết thúc');
        });

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
        $form->select('id_mon_hoc', 'Môn học')->options(MonHoc::all()->pluck('ten', 'id'))->rules('required');
        $form->select('id_gv', 'Giảng viên')->options(Administrator::where('kieu_nguoi_dung', 0)
            ->pluck('name', 'id'))->rules('required');
        $form->hidden('sl_hien_tai', 'Số lượng hiện tại')->value('0');
        $form->number('sl_min', 'Số lượng tối thiểu')->rules('integer|min:5');
        $form->number('sl_max', 'Số lượng tối đa')->rules('integer|min:10');
        $form->date('ngay_bat_dau', 'Ngày bắt đầu')->rules('required');
        $form->date('ngay_ket_thuc', 'Ngày kết thúc')->rules('required');
        $form->hidden('Created at');
        $form->hidden('Updated at');
        $form->hasManyCustom('thoi_gian_hoc', 'Thời gian học', function (NestedForm $form) {
            $options = ['2'=>'Thứ 2', '3'=>'Thứ 3', '4'=>'Thứ 4', '5'=>'Thứ 5', '6'=>'Thứ 6', '7'=>'Thứ 7', '8'=>'Chủ nhật'];
            $form->select('ngay', 'Ngày học')->options($options);
            $form->select('id_phong_hoc', 'Phòng học')->options(PhongHoc::all()->pluck('ten', 'id'));
            $timeStart = TietHoc::all()->pluck('gio_bat_dau', 'gio_bat_dau' );
            $timeEnd = TietHoc::all()->pluck('gio_ket_thuc', 'gio_ket_thuc' );
            $form->select('gio_bat_dau', 'Giờ học bắt đầu')->options($timeStart);
            $form->select('gio_ket_thuc', 'Giờ học kết thúc')->options($timeEnd);
        })->rules('required')->useTab();
        $form->saving(function (Form $form) use ($currentPath) {

            //Kiểm tra có trùng lịch giáo viên không
            $idGv = $form->id_gv;
            $idDotDangKy = $form->id_dot_dang_ky;
            $lopHocPhanGv = LopHocPhan::where('id_gv', $idGv)->where('id_dot_dang_ky', $idDotDangKy)->pluck('id')->toArray();
            if($currentPath == "admin/lop-hoc-phan/{lop_hoc_phan}") {
                if (($key = array_search($form->model()->id, $lopHocPhanGv )) !== false) {
                    unset($lopHocPhanGv[$key]);
                }
                $thoiGianDays = ThoiGianHoc::whereIn('id_hoc_phan_dang_ky', $lopHocPhanGv)->get()->toArray();
            } else {
                $thoiGianDays = ThoiGianHoc::whereIn('id_hoc_phan_dang_ky',$lopHocPhanGv)->get()->toArray();
            }
            if($form->thoi_gian_hoc) {
                foreach ($form->thoi_gian_hoc as $day) {
                    foreach ($thoiGianDays as $thoiGianDay) {
                        if ($day['ngay'] == $thoiGianDay['ngay']) {
                            if (
                                ($day['gio_ket_thuc'] > $thoiGianDay['gio_bat_dau'] && $day['gio_ket_thuc'] <= $thoiGianDay['gio_ket_thuc']) ||
                                ($day['gio_bat_dau'] >= $thoiGianDay['gio_bat_dau'] && $day['gio_bat_dau'] < $thoiGianDay['gio_ket_thuc']) ||
                                ($day['gio_bat_dau'] >= $thoiGianDay['gio_bat_dau'] && $day['gio_ket_thuc'] <= $thoiGianDay['gio_ket_thuc'])  ||
                                ($day['gio_bat_dau'] <= $thoiGianDay['gio_bat_dau'] && $day['gio_ket_thuc'] >= $thoiGianDay['gio_ket_thuc'])
                            ) {
                                $error = new MessageBag([
                                    'title' => 'Lỗi',
                                    'message' => 'Giảng viên đã có giờ dạy này ',
                                ]);
                                return back()->with(compact('error'));
                            }
                        }
                    }
                }
            }

            //Kiểm tra giờ học
            if($form->thoi_gian_hoc) {
                foreach($form->thoi_gian_hoc as $tgHoc) {
                    if($tgHoc['gio_bat_dau'] >= $tgHoc['gio_ket_thuc']) {
                        $error = new MessageBag([
                            'title'   => 'Lỗi',
                            'message' => 'Giờ học bắt đầu không được lớn hơn hoặc bằng giờ học kết thúc',
                        ]);
                        return back()->with(compact('error'));
                    }
                }
            }

            //Kiểm tra phòng học
            $lopHocPhan = LopHocPhan::where('id_dot_dang_ky', $form->id_dot_dang_ky)->pluck('id')->toArray();
            if($currentPath == "admin/lop-hoc-phan/{lop_hoc_phan}") {
                $thoiGianHocs = ThoiGianHoc::where('id_hoc_phan_dang_ky', '!=',$form->model()->id)
                    ->whereIn('id_hoc_phan_dang_ky', $lopHocPhan)->get()->toArray();
            } else {
                $thoiGianHocs = ThoiGianHoc::all()->whereIn('id_hoc_phan_dang_ky', $lopHocPhan)->toArray();
            }
            if($form->thoi_gian_hoc) {
                foreach ($form->thoi_gian_hoc as $day) {
                    foreach ($thoiGianHocs as $thoiGianHoc) {
                        if ($day['ngay'] == $thoiGianHoc['ngay'] && $day['id_phong_hoc'] == $thoiGianHoc['id_phong_hoc']) {
                            if (
                                ($day['gio_ket_thuc'] > $thoiGianHoc['gio_bat_dau'] && $day['gio_ket_thuc'] <= $thoiGianHoc['gio_ket_thuc']) ||
                                ($day['gio_bat_dau'] >= $thoiGianHoc['gio_bat_dau'] && $day['gio_bat_dau'] < $thoiGianHoc['gio_ket_thuc']) ||
                                ($day['gio_bat_dau'] >= $thoiGianHoc['gio_bat_dau'] && $day['gio_ket_thuc'] <= $thoiGianHoc['gio_ket_thuc'])  ||
                                ($day['gio_bat_dau'] <= $thoiGianHoc['gio_bat_dau'] && $day['gio_ket_thuc'] >= $thoiGianHoc['gio_ket_thuc'])
                            ) {
                                $error = new MessageBag([
                                    'title' => 'Lỗi',
                                    'message' => 'Giờ học này đã có lớp học ',
                                ]);
                                return back()->with(compact('error'));
                            }
                        }
                    }
                }
            }
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
