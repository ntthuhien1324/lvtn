<?php

namespace App\Http\Controllers;

use App\Http\Extensions\ContentSinhVien;
use App\Models\Administrator;
use App\Models\DotDangKy;
use App\Models\KetQuaDangKy;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\PhongHoc;
use App\Models\ThoiGianHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use App\Http\Extensions\Grid;

use Illuminate\Support\Facades\Auth;


class KetQuaDangKyController extends Controller
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
            ->header('Kết quả đăng ký môn học')
            ->description('Danh sách môn học')
            ->body(
                view('vendor.details',[
                    'template_body_name' => 'user.ket_qua_dang_ky.info',
                    'form' => $this->formDotDangKy(),
                    'grid' => $this->grid()->render()
                ])
            );
    }

    protected function formDotDangKy()
    {
        $form = new Form(new DotDangKy());
        $form->registerBuiltinFields();
        $id = Auth::User()->id;
        $arrIdDotDangKy = KetQuaDangKy::where('id_sv',$id)->distinct()->pluck('id_dot_dang_ky')->toArray();
        $options = DotDangKy::whereIn('id',$arrIdDotDangKy)->orderBy('id', 'DESC')->pluck('ten', 'id')->toArray();
        $form->select('id_dot_dang_ky', 'Thời gian')->options($options)->attribute(['id' => 'ketQuaDangKy']);
        $form->footer(function ($footer) {
            // disable reset btn
            $footer->disableReset();

            // disable `View` checkbox
            $footer->disableViewCheck();

            // disable `Continue editing` checkbox
            $footer->disableEditingCheck();

            $footer->disableSubmit();

        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableView();
            $tools->disableDelete();
        });
//        return $form;
    }
    protected function grid()
    {
        $grid = new Grid(new KetQuaDangKy());

        $user = Auth::user();
        //ưu tiên theo đợt đang mở trước
        $dotDangKy = DotDangKy::where('trang_thai',1)->orderByDesc('id')->first();
        if(empty($dotDangKy)){
            $dotDangKySinhVien = KetQuaDangKy::where('id_sv', $user->id)->orderByDesc('id_dot_dang_ky')->first();
            $dotDangKy = DotDangKy::where('id',$dotDangKySinhVien->id_dot_dang_ky)->first();
        }
        $grid->model()->where('id_dot_dang_ky', $dotDangKy->id)->where('id_sv', $user->id);
        $grid->column('Mã học phần')->style("text-align: center;")->display(function () {
            $lopHocPhan = LopHocPhan::where('id',$this->id_hoc_phan_dang_ky)->first();
            if (!empty($lopHocPhan)) {
                return $lopHocPhan->id;
            } else {
                return '';
            }
        });
        $grid->id_mon_hoc('Môn học')->display(function () {
            $idMonHoc = $this->id_mon_hoc;
            if (!empty($idMonHoc)) {
                return MonHoc::find($idMonHoc)->ten;
            } else {
                return '';
            }
        });
        $grid->column('Phòng')->style("text-align: center;")->display(function () {
            $idPhong = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('id_phong_hoc')->toArray();
            $phong = PhongHoc::whereIn('id', $idPhong)->pluck('ten')->toArray();
            $phong = array_map(function ($phong) {
                return "<span class='label label-success'>{$phong}</span>";
            }, $phong);
            return join('&nbsp;', $phong);
        });
        $grid->column('Buổi học')->style("text-align: center;")->display(function () {
            $day = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('ngay')->toArray();
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
            $timeStart = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('gio_bat_dau')->toArray();
            $timeEnd = ThoiGianHoc::where('id_hoc_phan_dang_ky', $this->id_hoc_phan_dang_ky)->pluck('gio_ket_thuc')->toArray();
            $time = array_map(function ($timeStart, $timeEnd) {
                return "<span class='label label-success'>{$timeStart} - {$timeEnd}</span>";
            }, $timeStart, $timeEnd);
            return join('&nbsp;', $time);
        });

        $grid->column('Giảng viên')->display(function () {
            $lopHocPhan = LopHocPhan::where('id',$this->id_hoc_phan_dang_ky)->first();
            if (!empty($lopHocPhan)) {
                $gv = Administrator::find($lopHocPhan->id_gv);
                if ($gv) {
                    return $gv->name;
                } else {
                    return '';
                }
            } else {
                return '';
            }
        });
        $grid->column('Ngày bắt đầu')->style("text-align: center;")->display(function (){
            $lopHocPhan = LopHocPhan::find($this->id_hoc_phan_dang_ky);
            if(!empty($lopHocPhan->ngay_bat_dau)){
                return $lopHocPhan->ngay_bat_dau;
            } else {
                return '';
            }
        });
        $grid->column('Ngày kết thúc')->style("text-align: center;")->display(function (){
            $lopHocPhan = LopHocPhan::find($this->id_hoc_phan_dang_ky);
            if(!empty($lopHocPhan->ngay_ket_thuc)){
                return $lopHocPhan->ngay_ket_thuc;
            } else {
                return '';
            }
        });
        $grid->column('Số tín chỉ hiện tại')->display(function () use ($dotDangKy){
            $idUser = Auth::user()->id;
            $idMonHoc = KetQuaDangKy::where('id_sv', $idUser)->where('id_dot_dang_ky',  $dotDangKy->id)->pluck('id_mon_hoc');
            $monHoc = MonHoc::find($idMonHoc);
            $sumTC = 0;
            foreach ($monHoc as $mon) {
                $sumTC += $mon->so_tin_chi;
            }
            return $sumTC;

        });

        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableFilter();
        if($dotDangKy->trang_thai == 0) {
            $grid->disableActions();
        }
        $grid->actions(function ($actions){
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->disableView();
            $actions->append('<a href="javascript:void(0);" data-id="' . $this->row->id_hoc_phan_dang_ky . '" style="display:block;"  class="btn btn-danger btnTotal btnCancel" ><i class="glyphicon glyphicon-trash"></i> &nbsp Hủy bỏ </a>');
        });
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
        cancelButtonText: "$cancel",
        preConfirm: function() {
            return new Promise(function(resolve) {
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
                                     },
                                     function() {
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
    });
});
SCRIPT;
        Admin::script($script);
        return $grid;
    }

}