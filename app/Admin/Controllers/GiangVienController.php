<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 19/11/2018
 * Time: 1:36 SA
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Grid;
use App\Http\Controllers\Controller;
use App\Models\Khoa;
use App\Models\Lop;
use App\Models\SinhVien;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GiangVienController extends Controller
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
            ->header('Giảng viên')
            ->description('Danh sách lớp')
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
        $lop = Lop::findOrFail($id);
        return $content
            ->header('Lớp')
            ->description($lop->ten)
            ->body($this->detailsView($id));
    }

    public function detailsView($id)
    {
        $show = $this->detail($id);
        $gridSv = $this->gridSv($id)->render();
        return view('vendor.details',
            [
                'template_body_name' => 'vendor.admin.giang_vien.info',
                'form' => $show,
                'gridSv' => $gridSv
            ]
        );
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lop());

        $grid->model()->orderBy('created_at', 'DESC');
        $user = Admin::user();
        $idUser = $user->id;
        $grid->model()->where('id_gv', $idUser);
        $grid->ten('Tên lớp')->display(function ($ten) {
            return '<a href="/admin/giang-vien/lop/' . $this->id . '">' . $ten . '</a>';
        });
        $grid->id_khoa('Tên khoa')->display(function ($idKhoa) {
            return Khoa::find($idKhoa)->ten;
        });
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });
        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->like('ten', 'Tên');
            $filter->in('id_khoa', 'Tên khoa')->select(Khoa::all()->pluck('ten','id'));
        });
        $grid->disableCreateButton();
        $grid->disableRowSelector();
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
        $show = new Show(Lop::findOrFail($id));

        $show->ten('Tên lớp');
        $show->id_khoa('Tên khoa')->as(function ($idKhoa) {
            return Khoa::find($idKhoa)->ten;
        });
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });;

        return $show;
    }

    protected function gridSv($idLop)
    {
        $grid = new Grid(new SinhVien());

        $grid->model()->where('id_lop', $idLop);
        $grid->mssv('Mã số sinh viên')->sortable();
        $grid->ho_ten('Họ và tên');
        $grid->ngay_sinh('Ngày sinh');
        $grid->email('Email');
        $grid->id_lop('Lớp')->display(function ($idLop) {
            if ($idLop) {
                return Lop::find($idLop)->ten;
            } else {
                return 'Không có';
            }
        });
        $grid->nam_nhap_hoc('Năm nhập học')->sortable();
        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->like('mssv', 'MSSV');
            $filter->like('ho_ten', 'Họ và tên');
            $filter->like('email', 'Email');
            $filter->equal('nam_nhap_hoc', 'Năm nhập học')->year();
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->append('<a href="/admin/giang-vien/thong-tin-sinh-vien/' . $actions->getKey() . '"><i class="fa fa-eye"></i> KQDK | </a>');
            $actions->append('<a href="/admin/giang-vien/diem-sinh-vien/' . $actions->getKey() . '"><i class="fa fa-search-plus"></i> Xem điểm</a>');

        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();

        return $grid;
    }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ClassSTU::class, function (Form $form) {
            $form->text('name', 'Tên lớp')->rules(function ($form) {
                return 'required|unique:class,name,' . $form->model()->id . ',id';
            })->readOnly();
            $form->select('id_department', 'Tên khoa')->options(Department::all()->pluck('name', 'id'))->rules('required')->readOnly();
            $form->disableReset();
        });
    }



    //todo start subject-register
    #region subject register
    //subject register of teacher
    public function subjectRegister()
    {
        return Admin::content(function (Content $content) {

            $content->header('Giảng viên');
            $content->description('Xem lịch, TKB');

            $content->body(
                view('vendor.details',
                    [
                        'template_body_name' => 'admin.Teacher.SubjectRegister.info',
                        'formTimeRegister' => $this->formTimeRegister(),
                        'gridSubjectRegister' => $this->gridSubjectRegister()
                    ]));
        });
    }

    protected function formTimeRegister()
    {
        return Admin::form(TimeRegister::class, function (Form $form) {
            $user = Admin::user();
            $idUser = $user->id;
            $timeRegisterTeacher = SubjectRegister::where('id_user_teacher', $idUser)->pluck('id_time_register')->toArray();
            $form->select('id_time_register', 'Thời gian')->options(TimeRegister::whereIn('id', $timeRegisterTeacher)->orderBy('id', 'DESC')->pluck('name', 'id'));

            $form->disableReset();
            $form->disableSubmit();

        });
    }

    protected function gridSubjectRegister()
    {
        return Admin::grid(SubjectRegister::class, function (Grid $grid)  {
            $user = Admin::user();
            $idUser = $user->id;
            $subjectRegister = SubjectRegister::where('id_user_teacher', $idUser)->orderBy('id_time_register', 'DESC')->first();
            if(!empty($subjectRegister)) {
                $grid->model()->where('id_time_register', $subjectRegister->id_time_register)->where('id_user_teacher', $idUser);
            } else {
                $grid->model()->where('id', '-1');
            }
            $grid->id('Mã học phần')->display(function ($name) {
                return '<a href="/admin/teacher/subject-register/' . $this->id . '/details">' . $name . '</a>';
            });
            $grid->id_subjects('Môn học')->display(function ($idSubject) {
                if ($idSubject) {
                    $name = Subjects::find($idSubject)->name;
                    return "<span class='label label-info'>{$name}</span>";
                } else {
                    return '';
                }
            });
            $grid->column('Phòng')->display(function () {
                $idClassroom = TimeStudy::where('id_subject_register', $this->id)->pluck('id_classroom')->toArray();
                $classRoom = Classroom::whereIn('id', $idClassroom)->pluck('name')->toArray();
                $classRoom = array_map(function ($classRoom) {
                    return "<span class='label label-success'>{$classRoom}</span>";
                }, $classRoom);
                return join('&nbsp;', $classRoom);
            });
            $grid->column('Buổi học')->display(function () {
                $day = TimeStudy::where('id_subject_register', $this->id)->pluck('day')->toArray();
                $day = array_map(function ($day) {
                    switch ($day) {
                        case 2:
                            $day = 'Thứ 2';
                            break;
                        case 3:
                            $day = 'Thứ 3';
                            break;
                        case 4:
                            $day = 'Thứ 4';
                            break;
                        case 5:
                            $day = 'Thứ 5';
                            break;
                        case 6:
                            $day = 'Thứ 6';
                            break;
                        case 7:
                            $day = 'Thứ 7';
                            break;
                        case 8:
                            $day = 'Chủ nhật';
                            break;
                    }
                    return "<span class='label label-success'>{$day}</span>";
                }, $day);
                return join('&nbsp;', $day);
            });
            $grid->column('Thời gian học')->display(function () {
                $timeStart = TimeStudy::where('id_subject_register', $this->id)->pluck('time_study_start')->toArray();
                $timeEnd = TimeStudy::where('id_subject_register', $this->id)->pluck('time_study_end')->toArray();
                $time = array_map(function ($timeStart, $timeEnd) {
                    return "<span class='label label-success'>{$timeStart} - {$timeEnd}</span>";
                }, $timeStart, $timeEnd);
                return join('&nbsp;', $time);
            });
            $grid->id_user_teacher('Giảng viên')->display(function ($id_user_teacher) {
                if ($id_user_teacher) {
                    $teacher = UserAdmin::find($id_user_teacher);
                    if ($teacher) {
                        return $teacher->name;
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }
            });
            $grid->qty_current('Số lượng hiện tại');

            $grid->date_start('Ngày bắt đầu');
            $grid->date_end('Ngày kết thúc');
            //action
            $grid->actions(function ($actions) {
                $actions->disableEdit();
                $actions->disableDelete();
                $actions->append('<a href="/admin/teacher/subject-register/' . $actions->getKey() . '/details"><i class="fa fa-eye"></i></a>');
            });
            $grid->disableFilter();
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->like('id', 'Mã học phần');
//                $filter->in('id_subjects', 'Tên môn học')->multipleSelect(Subjects::all()->pluck('name', 'id'));
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->whereIn('id_subjects', $input);
                }, 'Tên môn học')->multipleSelect(Subjects::all()->pluck('name', 'id'));
                $filter->in('id_user_teacher', 'Giảng viên')->multipleSelect(UserAdmin::where('type_user', 0)->pluck('name', 'id'));
                $filter->in('id_time_register', 'TG Đăng ký')->multipleSelect(TimeRegister::all()->pluck('name','id'));
                $filter->like('qty_current', 'SL hiện tại');
                $filter->date('date_start', 'Ngày bắt đầu');
                $filter->date('date_end', 'Ngày kết thúc');
                $filter->between('created_at', 'Tạo vào lúc')->datetime();
            });
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableRowSelector();
        });
    }

    public function detailsSubjectRegister($id)
    {
        return Admin::content(
            function (Content $content) use ($id) {
                $class = SubjectRegister::findOrFail($id);
                $content->header('Lớp HP');
                $content->description($class->id);
                $content->body($this->detailsViewSubjectRegister($id));
            });
    }

    public function detailsViewSubjectRegister($id)
    {
        $formSubjectRegister = $this->formSubjectRegister()->view($id);
        $gridStudentSubject = $this->gridStudentSubject($id);
        return view('vendor.details',
            [
                'template_body_name' => 'admin.Teacher.StudentSubjectRegister.info',
                'formSubjectRegister' => $formSubjectRegister,
                'gridStudentSubject' => $gridStudentSubject
            ]
        );
    }

    protected function formSubjectRegister()
    {
        return Admin::form(SubjectRegister::class, function (Form $form) {
            $script = <<<EOT
        $(function () {
            var url = window.location.href;
            var action_link =url.split("/");
            var action  = action_link[action_link.length-1]
            if(action=="details")
            {
                $('.remove').remove();
                $('.add').remove();
            }

        });
EOT;
            Admin::script($script);
            $form->text('id', 'Mã học phần')->rules(function ($form) {
                return 'required|unique:subject_register,' . $form->model()->id . ',id';
            })->readOnly();
            $form->select('id_subjects', 'Môn học')->options(Subjects::all()->pluck('name', 'id'))->rules('required')->readOnly();
            $form->select('id_user_teacher', 'Giảng viên')->options(UserAdmin::where('type_user', '0')->pluck('name', 'id'))->rules('required')->readOnly();
            $form->date('date_start', 'Ngày bắt đầu')->placeholder('Ngày bắt đầu')->rules('required')->readOnly();
            $form->date('date_end', 'Ngày kết thúc')->placeholder('Ngày kết thúc')->rules('required')->readOnly();
            $form->disableReset();
            $form->hasMany('time_study', 'Thời gian học', function (Form\NestedForm $form) {
                $options = ['2' => 'Thứ 2', '3' => 'Thứ 3', '4' => 'Thứ 4', '5' => 'Thứ 5', '6' => 'Thứ 6', '7' => 'Thứ 7', '8' => 'Chủ nhật'];
                $form->select('day', 'Buổi học')->options($options)->readOnly();
                $form->select('id_classroom', 'Phòng học')->options(Classroom::all()->pluck('name', 'id'))->rules('required')->readOnly();
                $form->time('time_study_start', 'Giờ học bắt đầu')->readOnly();
                $form->time('time_study_end', 'Giờ học kết thúc')->readOnly();
            })->rules('required');
        });
    }

    protected function gridStudentSubject($idSubjectRegister)
    {
        return Admin::grid(ResultRegister::class, function (Grid $grid) use ($idSubjectRegister) {
            $grid->resource('/admin/teacher/point');
            $user = Admin::user();
            $idUser = $user->id;
            $grid->model()->where('id_subject_register', $idSubjectRegister);
            $grid->id_user_student('MSSV')->display(function () {
                if (StudentUser::find($this->id_user_student)->code_number) {
                    return StudentUser::find($this->id_user_student)->code_number;
                } else {
                    return '';
                }
            })->sortable();
            $grid->column('Họ')->display(function () {
                if (StudentUser::find($this->id_user_student)->first_name) {
                    return StudentUser::find($this->id_user_student)->first_name;
                } else {
                    return '';
                }
            });
            $grid->column('Tên')->display(function () {
                if (StudentUser::find($this->id_user_student)->last_name) {
                    return StudentUser::find($this->id_user_student)->last_name;
                } else {
                    return '';
                }
            });
            $grid->id_subject_register('Mã HP')->display(function ($idSubjectRegister) {
                if (SubjectRegister::find($idSubjectRegister)->id) {
                    return SubjectRegister::find($idSubjectRegister)->id;
                } else {
                    return '';
                }
            })->sortable();
            $grid->id_subject('Môn')->display(function ($idSubject) {
                if (Subjects::find($idSubject)->name) {
                    return Subjects::find($idSubject)->name;
                } else {
                    return '';
                }
            });
            $grid->column('Lớp')->display(function () {
                $idClass = StudentUser::find($this->id_user_student)->id_class;
                $name = ClassSTU::find($idClass)->name;
                return "<span class='label label-info'>{$name}</span>";
            });
            $grid->attendance('Điểm chuyên cần')->sortable();
            $grid->mid_term('Điểm giữa kì')->sortable();
            $grid->end_term('Điểm cuối kì')->sortable();
            $grid->column('Điểm tổng kết')->display(function () {
                if(!$this->attendance || !$this->mid_term || !$this->end_term) {
                    return 'X';
                } else {
                    return (($this->attendance * $this->rate_attendance) +
                            ($this->mid_term * $this->rate_mid_term) +
                            ($this->end_term * $this->rate_end_term)) / 100;
                }

            })->setAttributes(['class'=>'finalPoint']);
            $idTimeRegister = ResultRegister::where('id_subject_register', $idSubjectRegister)->pluck('time_register');
            $timeRegister = TimeRegister::find($idTimeRegister)->first();
            $grid->filter(function($filter)use ($idSubjectRegister) {
                $filter->disableIdFilter();
                $filter->where(function ($query){
                    $input = $this->input;
                    $idUser = StudentUser::where('code_number','like', '%'.$input.'%')->pluck('id')->toArray();
                    $query->whereIn('id_user_student', $idUser);
                }, 'MSSV');
                $filter->where(function ($query){
                    $input = $this->input;
                    $idUser = StudentUser::where('first_name','like', '%'.$input.'%')->pluck('id')->toArray();
                    $query->whereIn('id_user_student', $idUser);
                }, 'Họ SV');
                $filter->where(function ($query){
                    $input = $this->input;
                    $idUser = StudentUser::where('last_name','like', '%'.$input.'%')->pluck('id')->toArray();
                    $query->whereIn('id_user_student', $idUser);
                }, 'Tên SV');
                $filter->where(function ($query){
                    $input = $this->input;
                    $idUser = StudentUser::where('id_class', $input)->pluck('id')->toArray();
                    $query->whereIn('id_user_student', $idUser);
                }, 'Lớp')->select(ClassSTU::all()->pluck('name','id'));
                $filter->where(function ($query) use ($idSubjectRegister) {
                    $input = $this->input;
                    $idResultRegister = ResultRegister::where('attendance',$input)->where('id_subject_register',$idSubjectRegister)
                        ->pluck('id')->toArray();
                    $query->whereIn('id', $idResultRegister);
                }, 'Điểm CC');
                $filter->where(function ($query) use ($idSubjectRegister) {
                    $input = $this->input;
                    $idResultRegister = ResultRegister::where('mid_term',$input)->where('id_subject_register',$idSubjectRegister)
                        ->pluck('id')->toArray();
                    $query->whereIn('id', $idResultRegister);
                }, 'Điểm GK');
                $filter->where(function ($query) use ($idSubjectRegister) {
                    $input = $this->input;
                    $idResultRegister = ResultRegister::where('end_term',$input)->where('id_subject_register',$idSubjectRegister)
                        ->pluck('id')->toArray();
                    $query->whereIn('id', $idResultRegister);
                }, 'Điểm CK');
                $filter->where(function ($query) use ($idSubjectRegister) {
                    $input = $this->input;
                    $idFinal = ResultRegister::whereRaw("((attendance *rate_attendance)+(mid_term*rate_mid_term)+(end_term*rate_end_term))/100 = ".$input)
                        ->pluck('id')->toArray();
                    $query->whereIn('id', $idFinal);
                }, 'Điểm TK');
            });
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableRowSelector();
        });
    }
    #endregion




}
