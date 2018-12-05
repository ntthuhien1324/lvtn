<?php

namespace App\Http\Controllers;

use App\Http\Extensions\ContentSinhVien;
use App\Http\Extensions\Grid;
use App\Models\MonHoc;
use App\Models\MonHocSongSong;
use App\Models\MonHocTruocSau;
use Encore\Admin\Controllers\HasResourceActions;

class MonHocTruocSauController extends Controller
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
            ->header('Môn học trước sau')
            ->description('Danh sách môn học trước sau')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new MonHocTruocSau());

        $grid->id_mon_hoc_truoc('Môn học trước')->display(function ($id_mon_hoc_1) {
            $monHoc = MonHoc::find($id_mon_hoc_1);
            $tenMonHoc = !empty($monHoc) ? $monHoc->ten : '';
            return $tenMonHoc;
        });
        $grid->id_mon_hoc_sau('Môn học sau')->display(function ($id_mon_hoc_2) {
            $monHoc = MonHoc::find($id_mon_hoc_2);
            $tenMonHoc = !empty($monHoc) ? $monHoc->ten : '';
            return $tenMonHoc;
        });
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->in('id_mon_hoc_truoc','Môn học trước')->multipleSelect(MonHoc::all()->pluck('ten','id'));
            $filter->in('id_mon_hoc_sau','Môn học sau')->multipleSelect(MonHoc::all()->pluck('ten','id'));
        });
        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableExport();

        return $grid;
    }
}