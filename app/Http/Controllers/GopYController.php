<?php
namespace App\Http\Controllers;

use App\Http\Extensions\ContentSinhVien;
use App\Models\GopY;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Illuminate\Support\Facades\Auth;

class GopYController extends Controller
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
            ->header('Góp ý kiến')
            ->description('Ý kiến')
            ->body($this->form());
    }

    protected function form() {
        $form = new Form(new GopY());
        $id = Auth::User()->id;
        $form->registerBuiltinFields();
        $form->setAction('/user/gop-y');
        $form->hidden('trang_thai')->value(0);
        $form->hidden('id_user')->value($id);
        $form->text('tieu_de','Tiêu đề')->rules('required');
        $form->textarea('noi_dung','Nội dung')->rules('required');
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableView();
            $tools->disableDelete();
        });
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
        });

        return $form;
    }
}