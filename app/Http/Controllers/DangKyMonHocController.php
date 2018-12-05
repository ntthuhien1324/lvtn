<?php

namespace App\Http\Controllers;

use App\Http\Extensions\ContentSinhVien;
use App\Http\Extensions\Grid;
use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\HocKy;
use App\Models\KetQuaDangKy;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\MonHocHocKy;
use App\Models\NamHoc;
use App\Models\NhomMonHoc;
use App\Models\PhongHoc;
use App\Models\ThoiGianHoc;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DangKyMonHocController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(ContentSinhVien $content)
    {
        return $content
            ->header('Đăng ký môn học')
            ->description('Danh sách môn học')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new MonHoc());

        $grid->registerColumnDisplayer();
        $user = Auth::user();
        $namNhapHoc = $user->nam_nhap_hoc;

        //kiểm tra năm nhập học
        $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
        if ($dotDangKy) {

            //mở đăng ký môn học theo học kỳ
            $hocKy = $dotDangKy->hoc_ky;
            $idHocKy = HocKy::where('ten', $hocKy)->pluck('id');
            $idMonHoc = MonHocHocKy::whereIn('id_hoc_ky', $idHocKy)->orderBy('id_hoc_ky', 'DESC')->pluck('id_mon_hoc')->toArray();

            //sort follow semester
            $field = '';
            foreach ($idMonHoc as $id) {
                $field .= ('"'.$id.'"' . ',');
            }
            $field = substr($field, 0, strlen($field) - 1);
            //Lấy môn học đã học
            $idHocPhanDangKy = KetQuaDangKy::where('id_sv', $user->id)->where('da_hoc', 1)->pluck('id_hoc_phan_dang_ky')->toArray();
            $idMonHocDaHoc = LopHocPhan::whereIn('id', $idHocPhanDangKy)->pluck('id_mon_hoc')->toArray();

            //hiển thị các môn chưa học & trong đợt đăng kí đang mở
            $grid->model()->whereIn('id', $idMonHoc)->whereNotIn('id', $idMonHocDaHoc)->orderBy(DB::raw('FIELD(id, ' . $field . ')'));
        }

        $grid->id('Mã môn học')->style("text-align: center;");
        $grid->ten('Tên môn học')->display(function ($ten) {
            return '<a href="/user/dang-ky-mon-hoc/' . $this->id . '"  target="_blank" >' . $ten . '</a>';
        });

        $grid->so_tin_chi('Số tín chỉ')->style("text-align: center;");
        $grid->column('Nhóm môn')->display(function () {
            $monHoc = MonHoc::find($this->id);
            $tenNhom = $monHoc->nhom_mon_hoc()->pluck('ten')->toArray();
            $nhomMonHoc = array_map(function ($tenNhom){
                if($tenNhom) {
                    return "<span class='label label-primary'>{$tenNhom}</span>"  ;
                } else {
                    return '';
                }
            },$tenNhom);
            return join('&nbsp;', $nhomMonHoc);

        });
        $grid->column('Học kỳ - Năm')->style("text-align: center;")->display(function () {
            $id = $this->id;
            $monHoc = MonHoc::find($id);
            $arrayHocKy = $monHoc->hocKy()->pluck('id')->toArray();
            $tenHocKyNamHoc = array_map(function ($idHocKy) {
                $tenHocKy = HocKy::find($idHocKy)->ten;
                switch ($tenHocKy) {
                    case 0 :
                        $tenHocKy = 'Học kỳ hè';// học kỳ hè
                        break;
                    case 1:
                        $tenHocKy = 'Học kì 1';
                        break;
                    case 2:
                        $tenHocKy = 'Học kì 2';
                }
                $namHoc = HocKy::find($idHocKy)->namHoc()->get()->toArray();
                if(!empty($namHoc)) {
                    $tenNamHoc = $namHoc['0']['ten'];
                } else {
                    $tenNamHoc = '';
                }

                if(substr($tenNamHoc,10,1) % 2 == 0){
                    if($tenHocKy != 'Học kỳ hè') {
                        return "<span class='label label-info'>{$tenHocKy} - {$tenNamHoc}</span>"  ;
                    }
                } else {
                    return "<span class='label label-success'>{$tenHocKy} - {$tenNamHoc}</span>";
                }

            }, $arrayHocKy);
            return join('&nbsp;', $tenHocKyNamHoc);
        });
        $grid->column('Đăng ký')->style("text-align: center;")->display(function () {
            return '<a href="/user/dang-ky-mon-hoc/' . $this->id . '" data-id='.$this->id.' class="btn btn-md"  target="_blank" ><i class="fa fa-pencil-square-o fa-fw fa-1x"></i></a>';
        });
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('id', 'Mã môn học');
            $filter->like('ten', 'Tên môn học');
            $filter->like('so_tin_chi', 'Tín chỉ');
            $hocKys = HocKy::all()->toArray();
            $optionHocKy = [];
            foreach($hocKys as $hocKy) {
                if($hocKy['ten'] == 0) {
                    $optionHocKy += [$hocKy['id'] => 'Học kỳ hè'];
                } else {
                    $namHoc = NamHoc::where('id', $hocKy['id_nam_hoc'])->first();
                    $tenNamHoc = $namHoc->ten;
                    $optionHocKy += [$hocKy['id'] => 'Học kỳ '. $hocKy['ten']. ' - ' . $tenNamHoc];
                }
            }
            $filter->where(function ($query){
                $input = $this->input;
                $hocKy = HocKy::where('id',$input)->first();
                $idMonHoc = $hocKy->monHoc()->pluck('id')->toArray();
                $query->whereIn('id', $idMonHoc);
            }, 'Học kì')->select($optionHocKy);
            $filter->where(function ($query){
                $input = $this->input;
                $nhomMonHoc = NhomMonHoc::where('id',$input)->first();
                $idMonHoc = $nhomMonHoc->monHoc()->pluck('id')->toArray();
                $query->where(function ($query) use ($idMonHoc) {
                    $query->whereIn('id', $idMonHoc);
                });
            }, 'Nhóm môn học')->multipleSelect(NhomMonHoc::all()->pluck('ten', 'id'));
        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->disableActions();

        return $grid;
    }
    protected function gridMonHocDangKy($idMonHoc)
    {
        $grid = new Grid(new LopHocPhan());

        $script = <<<SCRIPT
    
// Kiểm tra môn học trước - sau

$.ajax({
    method: 'get',
    url: '/user/dang-ky-mon-hoc/$idMonHoc/kiem-tra-truoc-sau',
    data: {
        _method:'kiemTraTruocSau',
        _token:LA.token,
    },
    success: function (data) {
        if (typeof data === 'object') {
            if (data.status == false) {
                 swal({
                      type: 'error',
                      title:'Thông báo',
                      text: data.message,
                     },function() {
                        window.location.href= ('../../../user/dang-ky-mon-hoc');
                 });
            } 
        }
    }
});

//check subject parallel 

$.ajax({
    method: 'get',
    url: '/user/dang-ky-mon-hoc/$idMonHoc/kiem-tra-song-song',
    data: {
        _method:'kiemTraSongSong',
        _token:LA.token,
    },
    success: function (data) {
        if (typeof data === 'object') {
            if (data.status == false) {
                 swal({
                      type: 'error',
                      title:'Thông báo',
                      text: data.message,
                     },function() {
                        window.location.href= ('../../../user/dang-ky-mon-hoc');
                 });
            } 
        }
    }
});

SCRIPT;
        Admin::script($script);
        $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
        $grid->model()->where('id_mon_hoc', $idMonHoc)->where('id_dot_dang_ky', $dotDangKy->id);
        $grid->id('Mã học phần')->style("text-align: center;");
        $grid->id_mon_hoc('Môn học')->display(function ($idMonHoc) {
            if ($idMonHoc) {
                return MonHoc::find($idMonHoc)->ten;
            } else {
                return '';
            }
        });
        $grid->column('Phòng')->style("text-align: center;")->display(function () {
            $idPhongHoc = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('id_phong_hoc')->toArray();
            $phong = PhongHoc::whereIn('id', $idPhongHoc)->pluck('ten')->toArray();
            $phong = array_map(function ($phongHoc) {
                return "<span class='label label-success'>{$phongHoc}</span>";
            }, $phong);
            return join('&nbsp;', $phong);
        });
        $grid->column('Buổi học')->style("text-align: center;")->display(function () {
            $day = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('ngay')->toArray();
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
        $grid->column('Thời gian học')->style("text-align: center;")->display(function () {
            $gioBatDau = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('gio_bat_dau')->toArray();
            $gioKetThuc = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id)->pluck('gio_ket_thuc')->toArray();
            $time = array_map(function ($gioBatDau, $gioKetThuc) {
                return "<span class='label label-success'>{$gioBatDau} - {$gioKetThuc}</span>";
            }, $gioBatDau, $gioKetThuc);
            return join('&nbsp;', $time);
        });
        $grid->id_gv('Giảng viên')->display(function ($id_user_teacher) {
            if ($id_user_teacher) {
                $teacher = Administrator::find($id_user_teacher);
                if ($teacher) {
                    return $teacher->name;
                } else {
                    return '';
                }
            } else {
                return '';
            }
        });
        $grid->sl_hien_tai('Số lượng hiện tại')->style("text-align: center;");
        $grid->sl_max('Số lượng tối đa')->style("text-align: center;");
        $grid->ngay_bat_dau('Ngày bắt đầu')->style("text-align: center;");
        $grid->ngay_ket_thuc('Ngày kết thúc')->style("text-align: center;");

        $grid->disableExport();
        $grid->disableCreation();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->actions(function ($actions) use ($idMonHoc){
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->disableView();
            $actions->append('<a href="javascript:void(0);" data-id="' . $this->getKey() . '"  class="btn btn-danger btnTotal btnCancel" style="display: none;text-align:center;"><i class="glyphicon glyphicon-trash"></i> &nbsp Hủy bỏ </a>');
            $user = Auth::user();
            $idUser = $user->id;
            $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
            $idDotDangKy = $dotDangKy->id;
            $monHocDangKy = KetQuaDangKy::where('id_mon_hoc',$idMonHoc)
                ->where('id_sv', $idUser)
                ->where('id_hoc_phan_dang_ky', $idDotDangKy)
                ->first();
            if($monHocDangKy) {
                $idLopHocPhan = $monHocDangKy->id_hoc_phan_dang_ky;
                $script = <<<SCRIPT
$('.btnRegister').each(function(){
    var idRegister =$(this).data('id');
    if (idRegister == '$idLopHocPhan') {
        $('.btnCancel[data-id='+idRegister+']').css("display", "block");
    } else {
        $('.btnRegister[data-id='+idRegister+']').css("display", "block");
    }
});
SCRIPT;
                Admin::script($script);
            } else {
                $script = <<<SCRIPT
$('.btnRegister').css("display", "block");
SCRIPT;
                Admin::script($script);

            }

            //button Register (nút đăng kí)
            $actions->append('<a href="javascript:void(0);" data-id="' . $this->getKey() . '"  class="btn btn-primary btnRegister btnTotal" style="display: none;text-align:center;"  ><i class="fa fa-pencil-square-o"></i> &nbsp Đăng ký </a>');


        });

        $registerConfirm = trans('Bạn có chắc chắn muốn đăng ký không?');
        $confirm = trans('Đăng ký');
        $cancel = trans('Hủy bỏ');
        $cancelConfirm = trans('Bạn có chắc chắn muốn hủy không?');
        $confirmDelete = trans('Hủy đăng ký');

        $script = <<<SCRIPT
$('.btnCancel').unbind('click').click(function() {
    var id = $(this).data('id');
    swal({
        title: "$cancelConfirm",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "$confirmDelete",
        closeOnConfirm: false,
        cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'get',
            url: '/user/dang-ky-mon-hoc/' + id + '/huy-dang-ky',
            data: {
                _method:'huyDangKy',
                _token:LA.token,
            },
            success: function (data) {
                if (typeof data === 'object') {
                    if (data.status) {
                         swal({
                              title: "Hủy thành công", 
                              type: "success"
                             },function() {
                              location.reload();
                             
                         });
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

$('.btnRegister').unbind('click').click(function() {
    var id = $(this).data('id');
    swal({
        title: "$registerConfirm",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3c8dbc",
        confirmButtonText: "$confirm",
        closeOnConfirm: false,
        cancelButtonText: "$cancel",
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    method: 'get',
                    url: '/user/dang-ky-mon-hoc/' + id + '/ket-qua-dang-ky',
                    data: {
                        _method:'ketQuaDangKy',
                        _token:LA.token,
                    },
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.status) {
                                 swal({
                                      title: "Đăng ký thành công", 
                                      type: "success"
                                     },function() {
                                      location.reload();
                                     
                                 });
                            } else {
                                swal(data.message, '', 'error');
                            }
                        }
                    }
                });
            });
        }
    })
});

SCRIPT;
        Admin::script($script);

        return $grid;
    }

    public function show($id,ContentSinhVien $content)
    {
        $monHoc = MonHoc::findOrFail($id);
        return $content
            ->header('Môn học')
            ->description($monHoc->ten)
            ->body($this->detail($id));
    }

    public function detail($id)
    {
        $gridMonHocDangKy = $this->gridMonHocDangKy($id)->render();
        return view('vendor.details',
            [
                'template_body_name' => 'user.mon_hoc_dang_ky.info',
                'gridMonHocDangKy' => $gridMonHocDangKy

            ]
        );
    }
}
