<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/11/2018
 * Time: 1:20 CH
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Grid;
use App\Models\Administrator;
use App\Models\Lop;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Controllers\UserController as UserControllerBase;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Route;

class UserController extends UserControllerBase
{
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Tài khoản')
            ->description('Danh sách')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Sửa tài khoản')
            ->description(Administrator::find($id)->name)
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Tạo tài khoản')
            ->description('')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Administrator);
        $grid->id('ID')->sortable();
        $currentPath = Route::getFacadeRoot()->current()->uri();
        if ($currentPath == 'admin/user_gv') {
            $grid->model()->where('kieu_nguoi_dung', 0);
            $grid->disableCreateButton();
        } else if($currentPath == 'admin/user_admin') {
            $grid->model()->where('kieu_nguoi_dung', 1);
        }
        $grid->username(trans('admin.username'))->sortable();
        $grid->name(trans('admin.name'))->sortable();
        $grid->roles(trans('admin.roles'))->pluck('name')->label()->sortable();
        $grid->email('Email')->sortable();
        if ($currentPath == 'admin/user_gv') {
            $grid->column('Lớp')->display(function () {
                $idGV = $this->id;
                $arrTenLop = Lop::where('id_gv', $idGV)->pluck('ten')->toArray();
                $arrTenLop = array_map(function ($arrTenLop){
                    if($arrTenLop) {
                        return "<span class='label label-primary'>{$arrTenLop}</span>"  ;
                    } else {
                        return '';
                    }
                },$arrTenLop);
                return join('&nbsp;', $arrTenLop);
            })->sortable();
        }
        $grid->kieu_nguoi_dung('Loại tài khoản')->display(function ($idLoai){
            if($idLoai == 0) {
                return 'Giảng viên';
            } else if($idLoai == 1) {
                return 'Quản trị';
            }
        })->sortable();
        $grid->created_at(trans('admin.created_at'))->sortable();
        $grid->updated_at(trans('admin.updated_at'))->sortable();

        $grid->actions(function (Actions $actions) {
            $user = Administrator::find($actions->getKey());
            $roleUser = $user->roles()->first();
//                $role = ;
            if(!empty($roleUser->slug)) {
                $role = $roleUser->slug;
                if ($role == "administrator") {
                    $actions->disableDelete();
                }
            }

        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });
        $grid->filter(function ($filter) use ($currentPath){
            $filter->disableIdFilter();
            if ($currentPath == 'admin/user_gv') {
                $user = Administrator::where('kieu_nguoi_dung', 0);
            } else if($currentPath == 'admin/user_admin') {
                $user = Administrator::where('kieu_nguoi_dung', 1);
            }
            $filter->in('username', 'Tên đăng nhập')->multipleSelect($user->pluck('username','username'));
            $filter->in('name', 'Tên')->multipleSelect($user->pluck('name','name'));
            $filter->in('email', 'Email')->multipleSelect($user->pluck('email','email'));

            if ($currentPath == 'admin/user_gv') {
                $filter->where(function ($query) {
                    $input = $this->input;
                    $arrGV = Lop::whereIn('ten', $input)->pluck('id_gv')->toArray();
                    $query->whereIn('id', $arrGV);
                }, 'Lớp')->multipleSelect(Lop::all()->pluck('ten', 'ten'));
            }
            if ($currentPath == 'admin/user_admin') {
                $filter->equal('kieu_nguoi_dung', 'Loại tài khoản')->radio([0 => 'Giảng viên', 1=> 'Quản trị']);
            }
            $filter->between('created_at', 'Tạo vào lúc')->datetime();
        });
        $grid->disableExport();
        return $grid;

    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $form = new Form(new Administrator());

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))->rules(function ($form) {
            if (!$id = $form->model()->id) {
                return 'required|unique:admin_users,username';
            }
        });
        $form->text('name', trans('admin.name'))->rules('required');
        $form->email('email', 'Email');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);
        $form->radio('kieu_nguoi_dung', 'Loại tài khoản')->options([0 => 'Giảng viên', 1 => 'Quản trị'])->default(0);

        $form->multipleSelect('roles', trans('admin.roles'))->options(Role::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options(Permission::all()->pluck('name', 'id'));
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });
        return $form;
    }
}