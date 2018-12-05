<?php
namespace App\Http\Controllers;

use App\Admin\Extensions\Form;
use App\Http\Extensions\ContentSinhVien;
use App\Models\DangKyNgoaiKeHoach;
use App\Models\DotDangKy;
use App\Models\MonHoc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form\Tools;
use Illuminate\Support\Facades\Auth;


class DangKyNgoaiKeHoachController extends Controller
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
            ->header('Đăng ký ngoài kế hoạch')
            ->description('Danh sách môn học')
            ->body($this->form());
    }

    protected function form()
    {
        $form = new Form(new DangKyNgoaiKeHoach());
        $form->registerBuiltinFields();
        $id = Auth::User()->id;
        $form->setAction('/user/dang-ky-ngoai-ke-hoach');
        $form->hidden('id_user')->value($id);
        $form->select('id_mon_hoc', 'Môn học')->options(MonHoc::all()->pluck('ten', 'id'))->rules('required');
        $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
        $idDotDangKy = $dotDangKy->id;
        $form->hidden('id_dot_dang_ky');
        $form->saving(function (Form $form) use ($idDotDangKy){
            $form->id_dot_dang_ky = $idDotDangKy;
        });
        $form->footer(function ($footer) {

            // disable reset btn
            $footer->disableReset();

            // disable `View` checkbox
            $footer->disableViewCheck();

            // disable `Continue editing` checkbox
            $footer->disableEditingCheck();

        });
        $form->tools(function (Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            $tools->disableView();
        });
        return $form;
    }
}