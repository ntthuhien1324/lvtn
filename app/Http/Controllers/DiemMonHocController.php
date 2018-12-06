<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/12/2018
 * Time: 12:38 CH
 */

namespace App\Http\Controllers;

use App\Http\Extensions\ContentSinhVien;
use App\Http\Extensions\Grid;
use App\Models\DotDangKy;
use App\Models\KetQuaDangKy;
use App\Models\MonHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Support\Facades\Auth;

class DiemMonHocController extends Controller
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
            ->header('Điểm')
            ->description('Danh sách điểm')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new KetQuaDangKy());

        $userId = Auth::user()->id;
        $grid->model()->where('id_sv',$userId)->where('da_hoc',1)->orderByDesc('id_dot_dang_ky');
        $grid->column('Mã MH')->display(function(){
            $monHoc = MonHoc::find($this->id_mon_hoc);
            if($monHoc->id) {
                return $monHoc->id;
            } else {
                return '';
            }
        });
        $grid->id_mon_hoc('Tên môn học')->display(function ($id) {
            $monHoc = MonHoc::find($this->id_mon_hoc);
            if($monHoc->ten) {
                return $monHoc->ten;
            } else {
                return '';
            }
        });

        $grid->column('Số tín chỉ')->display(function () {
            $monHoc = MonHoc::find($this->id_mon_hoc);
            if($monHoc->so_tin_chi) {
                return $monHoc->so_tin_chi;
            } else {
                return '';
            }
        });

        $grid->column('Năm')->display(function () {
            $dotDangKy = DotDangKy::find($this->id_dot_dang_ky);
            $id = $dotDangKy->id;
            if($id % 2 == 0)
            {
                return "<span class='label label-info'>{$dotDangKy->ten}</span>";
            } else {
                return "<span class='label label-success'>{$dotDangKy->ten}</span>";
            }
        });
        $grid->tl_diem_giua_ky('%GK');
        $grid->tl_diem_cuoi_ky('%CK');
        $grid->diem_giua_ky('Điểm GK');
        $grid->diem_cuoi_ky('Điểm CK');
        $grid->column('Điểm TK')->display(function () {
            $final = (($this->diem_giua_ky * $this->tl_diem_giua_ky) +
                    ($this->diem_cuoi_ky * $this->tl_diem_cuoi_ky)) / 100;
            return "<b>{$final}</b>";
        });
        $grid->column('Kết quả')->display(function () {
            $final = (($this->diem_giua_ky * $this->tl_diem_giua_ky) +
                    ($this->diem_cuoi_ky * $this->tl_diem_cuoi_ky)) / 100;
            if($final < 4.1){
                return "<b>X</b>";
            }
            else
            {
                return "<b>Đạt</b>";
            }
        });
        $grid->column('Số tín chỉ hiện tại')->display(function () use ($userId) {
            $idMonHoc = KetQuaDangKy::where('id_sv', $userId)->pluck('id_mon_hoc');
            $monHoc = MonHoc::find($idMonHoc);
            $sumTC = 0;
            foreach ($monHoc as $mon) {
                $sumTC += $mon->so_tin_chi;
            }
            return $sumTC;

        });
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->disableFilter();

        return $grid;
    }
}