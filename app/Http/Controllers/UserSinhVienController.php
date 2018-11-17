<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 17/11/2018
 * Time: 1:59 CH
 */

namespace App\Http\Controllers;

use App\Admin\Extensions\Form;
use App\Http\Extensions\ContentSinhVien;
use App\Models\SinhVien;
use App\Models\ThongBao;
use Encore\Admin\Controllers\HasResourceActions;
use App\Http\Extensions\Grid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSinhVienController extends Controller
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
            ->header('Thông báo')
            ->description('Thông báo cho sinh viên')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, ContentSinhVien $content)
    {
        return $content
            ->header('Sửa')
            ->description()
            ->body($this->form()->edit($id));
    }

    protected function grid()
    {
        $grid = new Grid(new ThongBao());

        $grid->ten('Tên thông báo')->display(function ($ten) {
            if($this->url) {
                return '<a href="/uploads/'. $this->url .'" target="_blank">'. $ten .'</a> (Nhấn để tải file)';
            } else {
                return '<a target="_blank">'. $ten .'</a>';
            }
        });
        $grid->noi_dung('Nội dung');
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('ten', 'Tên thông báo');
        });
        $grid->created_at('Thời gian tạo')->setAttributes(['width'=>'15%']);
        $grid->disableCreateButton();
        //$grid->disableCreateButton();
        $grid->disableActions();
        $grid->disableExport();
        $grid->disableRowSelector();

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new SinhVien());

        $form->display('id', 'ID');
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }

    public function postlogin(Request $request)
    {
        $this->validate($request,[
            'mssv'=>'required',
            'password'=>'required|min:3|max:32'
        ],
            [
                'mssv.required'=>'Bạn chưa nhập mã số sinh viên',
                'password.required'=>'Bạn chưa nhập mật khẩu',
                'password.min'=>'Password không được nhỏ hơn 3 ký tự',
                'password.max'=>'Password không được lớn hơn 5 ký tự'
            ]);

        if(Auth::attempt(['mssv'=>$request->mssv,'password'=>$request->password]))
        {
            return redirect('user/sinh-vien');
        }
        else
        {
            return redirect('getLogin')->with('notification','Đăng nhập không thành công');
        }
    }
    public function logout() {
        Auth::guard('web')->logout();
        return redirect('getLogin');
    }
}
