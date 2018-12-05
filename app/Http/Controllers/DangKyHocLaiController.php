<?php

namespace App\Http\Controllers;


use App\Http\Extensions\ContentSinhVien;
use App\Http\Extensions\Grid;
use App\Models\DotDangKy;
use App\Models\HocKy;
use App\Models\KetQuaDangKy;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\MonHocHocKy;
use App\Models\NamHoc;
use App\Models\NhomMonHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DangKyHocLaiController extends Controller
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
            ->header('Đăng ký môn học cải thiện, học lại')
            ->description('Danh sách môn học')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new MonHoc());

        $grid->registerColumnDisplayer();
        $user = Auth::user();
        //$schoolYearUser = $user->school_year;
        //check school year
        $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
        if ($dotDangKy) {
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
            $grid->model()->whereIn('id', $idMonHoc)->whereIn('id', $idMonHocDaHoc)->orderBy(DB::raw('FIELD(id, ' . $field . ')'));
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
}
