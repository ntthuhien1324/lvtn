<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 17/11/2018
 * Time: 6:29 CH
 */

namespace App\Http\Middleware;

use App\Models\DotDangKy;
use App\Models\TrangThaiSinhVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class DangKyMonHocMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
        $user = Auth::user();
        $trangThaiSV = $user->id_trang_thai;
        $tenTrangThai = TrangThaiSinhVien::where('id', $trangThaiSV)->pluck('trang_thai')->toArray();

        //lấy năm vào học của user
        $namNhapHoc = $user->nam_nhap_hoc;
        $namNhapHoc = (string) $namNhapHoc;
        if($dotDangKy){
            //xét user có nằm trong đợt đăng kí hay không
            if(in_array($namNhapHoc, $dotDangKy->nam_nhap_hoc) || $dotDangKy->nam_nhap_hoc['0'] == "All")
            {
                //xét trạng thái user
                if($trangThaiSV > 5) {
                    $exception = new MessageBag([
                        'title' => 'Thông báo',
                        'message' => 'Sinh viên không được phép đăng kí vì '. $tenTrangThai['0'],
                    ]);
                    return redirect('/user/sinh-vien')->with(compact('exception'));
                } else {
                    if($dotDangKy) {
                        return $next($request);
                    } else {
                        $exception = new MessageBag([
                            'title' => 'Thông báo',
                            'message' => 'Hiện tại chưa có đợt đăng kí ',
                        ]);
                        return redirect('/user/sinh-vien')->with(compact('exception'));
                    }
                }
            } else {
                $exception = new MessageBag([
                    'title' => 'Thông báo',
                    'message' => 'Sinh viên không nằm trong khóa được đăng kí',
                ]);
                return redirect('/user/sinh-vien')->with(compact('exception'));
            }
        } else {

            $exception = new MessageBag([
                'title' => 'Thông báo',
                'message' => 'Sinh viên không nằm trong khóa được đăng kí',
            ]);
            return redirect('/user/sinh-vien')->with(compact('exception'));

        }
    }
}