<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/12/2018
 * Time: 12:37 SA
 */

namespace App\Http\Controllers;

use App\Admin\Extensions\Form;
use App\Http\Extensions\ContentSinhVien;
use App\Models\Lop;
use App\Models\SinhVien;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form\Tools;
use Illuminate\Support\Facades\Auth;

class ThongTinSinhVienController extends Controller
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
        $id = Auth::User()->id;
        return $content
            ->header('Thông tin cá nhân')
            ->description('Cá nhân')
            ->body($this->edit($id));
    }

    protected function edit($id, ContentSinhVien $content) {
        return $content
            ->header('Thông tin cá nhân')
            ->description(SinhVien::find($id)->ho_ten)
            ->body($this->form()->edit($id));
    }

    protected function form() {
        $form = new Form(new SinhVien());

        $form->registerBuiltinFields();
        $id = Auth::User()->id;
        $form->setAction('/user/thong-tin/'.$id);
        $user = SinhVien::find($id);
        $tenLop = Lop::find($user->id_lop)->ten;
        $form->html($user->mssv,'MSSV');
        $form->html($user->ho_ten,'Họ tên');
        $form->email('email', 'Email');
        $form->password('password', 'Mật khẩu')->rules('required|confirmed');
        $form->password('password_confirmation', 'Xác nhận mật khẩu')->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });
        $form->ignore(['password_confirmation', 'id_lop', 'nam_nhap_hoc', 'mssv', 'ho_ten']);

        $form->html($tenLop,'Lớp');
        $form->html($user->nam_nhap_hoc,'Năm nhập học');
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableReset();
        });
        $form->tools(function (Tools $tools) {
            $tools->disableList();
            $tools->disableView();
            $tools->disableDelete();
        });

        return $form;

    }
}