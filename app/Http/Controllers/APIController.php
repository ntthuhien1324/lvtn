<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 18/11/2018
 * Time: 1:26 SA
 */

namespace App\Http\Controllers;

use App\Models\DotDangKy;
use App\Models\KetQuaDangKy;
use App\Models\LopHocPhan;
use App\Models\MonHoc;
use App\Models\MonHocSongSong;
use App\Models\MonHocTruocSau;
use App\Models\ThoiGianHoc;
use App\Models\TiLeDiem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIController extends Controller
{
    public function kiemTraTruocSau(Request $request) {
        $idMonHoc = $request->id;
        $user = Auth::user();
        $idUser = $user->id;
        $idMonHocTruoc = MonHocTruocSau::where('id_mon_hoc_sau',$idMonHoc)->pluck('id_mon_hoc_truoc')->toArray();
        if (count($idMonHocTruoc) > 0) {
            $monHocTruoc = MonHoc::where('id',$idMonHocTruoc)->first();
            $demMonHocTruoc = KetQuaDangKy::where('id_sv', $idUser)->where('id_mon_hoc',$idMonHocTruoc)->where('da_hoc', 1)->get()->count();
            if ($demMonHocTruoc == 0){
                return response()->json([
                    'status'  => false,
                    'message' => trans('Bạn phải học '.$monHocTruoc->ten.' trước'),
                ]);
            }
        }

    }

    public function kiemTraSongSong(Request $request) {
        $idMonHoc = $request->id;
        $user = Auth::user();
        $idUser = $user->id;
        $idMonHoc1=MonHocSongSong::where('id_mon_hoc_2',$idMonHoc)->pluck('id_mon_hoc_1')->toArray();
        if(count($idMonHoc1) >0) {
            $monHocSongSong=MonHoc::where('id',$idMonHoc1)->first();
            $countDaHoc2 = KetQuaDangKy::where('id_sv', $idUser)->where('id_mon_hoc',$idMonHoc1)->where('da_hoc', 2)->get()->count();
            $countDaHoc1 = KetQuaDangKy::where('id_sv', $idUser)->where('id_mon_hoc',$idMonHoc1)->where('da_hoc', 1)->get()->count();
            if($countDaHoc2 > 0 || $countDaHoc1 > 0 ){

            } else {
                return response()->json([
                    'status'  => false,
                    'message' => trans('Bạn phải đăng ký môn '.$monHocSongSong->ten.' trước'),
                ]);
            }
        }
    }

    public function huyDangKy(Request $request) {
        $idHocPhanDangKy = $request->id;
        $user = Auth::user();
        $idUser = $user->id;
        $huyMonHoc = KetQuaDangKy::where('id_sv',$idUser)->where('id_hoc_phan_dang_ky', $idHocPhanDangKy)->first();
        $lopHocPhan = LopHocPhan::find($idHocPhanDangKy);
        if($huyMonHoc->delete()) {
            $slHienTai = $lopHocPhan->sl_hien_tai;
            $lopHocPhan->sl_hien_tai = $slHienTai - 1;
            if($lopHocPhan->save()) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('Hủy đăng ký thành công'),
                ]);
            }else {
                return response()->json([
                    'status'  => false,
                    'message' => trans('Hủy đăng ký không thành công'),
                ]);
            }
        }
    }

    public function ketQuaDangKy(Request $request){
        $idHocPhanDangKy = $request->id;
        $user = Auth::user();
        $idUser = $user->id;
        $dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
        $idDotDangKy = $dotDangKy->id;

        //get qty current
        $dangKyMonHoc = LopHocPhan::find($idHocPhanDangKy);
        $slHienTai = $dangKyMonHoc->sl_hien_tai;
        $slMax = $dangKyMonHoc->sl_max;


        //nếu đã đăng kí rồi thì không được đăng kí nữa
        $idMonHoc = LopHocPhan::where('id',$idHocPhanDangKy)->pluck('id_mon_hoc')->toArray();
        $countMonHoc = KetQuaDangKy::where('id_mon_hoc', $idMonHoc['0'])->where('id_sv', $idUser)->where('id_dot_dang_ky', $idDotDangKy)->get()->count();
        if($countMonHoc >= 1)
        {
            return response()->json([
                'status'  => false,
                'message' => trans('Bạn đã đăng kí môn học này'),
            ]);
        }

        //lấy số lượng tín chỉ được đăng kí tối đa
        $tcMax = $dotDangKy->tc_max;
        $idMonHoc = KetQuaDangKy::where('id_sv', $idUser)->where('id_dot_dang_ky', $idDotDangKy)->where('da_hoc', 2)->pluck('id_mon_hoc');
        $tcHienTaiSV = MonHoc::find($idMonHoc)->pluck('so_tin_chi')->sum();
        $idMonHoc = LopHocPhan::where('id',$idHocPhanDangKy)->pluck('id_mon_hoc');
        $tcMonHoc = MonHoc::find($idMonHoc)->pluck('so_tin_chi')->toArray();
        if(($tcHienTaiSV + $tcMonHoc['0']) > $tcMax) {
            return response()->json([
                'status'  => false,
                'message' => trans('Bạn đã đăng kí tối đa số tín chỉ'),
            ]);
        }

        //kiểm tra giờ học trùng
        $arrIdLopHocPHan = KetQuaDangKy::where('id_sv',$idUser)->where('id_hoc_phan_dang_ky', $idHocPhanDangKy)->pluck('id_hoc_phan_dang_ky')->toArray();
        $arrKetQuaDangKy = ThoiGianHoc::whereIn('id_hoc_phan_dang_ky', $arrIdLopHocPHan)->get()->toArray();
        $arrThoiGianHocSV = ThoiGianHoc::where('id_hoc_phan_dang_ky',$idHocPhanDangKy)->get()->toArray();

        foreach ($arrKetQuaDangKy as $dayAll){
            foreach ($arrThoiGianHocSV as $dayUser){
                if ($dayAll['ngay'] == $dayUser['ngay'] ) {
                    if (
                        ($dayAll['gio_ket_thuc'] > $dayUser['gio_bat_dau'] && $dayAll['gio_ket_thuc'] <= $dayUser['gio_ket_thuc']) ||
                        ($dayAll['gio_bat_dau'] >= $dayUser['gio_bat_dau'] && $dayAll['gio_bat_dau'] < $dayUser['gio_ket_thuc']) ||
                        ($dayAll['gio_bat_dau'] >= $dayUser['gio_bat_dau'] && $dayAll['gio_ket_thuc'] <= $dayUser['gio_ket_thuc'])  ||
                        ($dayAll['gio_bat_dau'] <= $dayUser['gio_bat_dau'] && $dayAll['gio_ket_thuc'] >= $dayUser['gio_ket_thuc'])
                    )
                    {
                        return response()->json([
                            'status'  => false,
                            'message' => trans('Bạn đã đăng kí giờ học này'),
                        ]);
                    }
                }
            }
        }

        //nếu số lượng hiện tại lớn hơn số lượng max thì không được đăng kí
        if($slHienTai >= $slMax) {
            return response()->json([
                'status'  => false,
                'message' => trans('Học phần đã hết chỗ'),
            ]);
        } else {
            $ketQuaDangKy = new KetQuaDangKy();
            $ketQuaDangKy->id_sv = $idUser;
            $ketQuaDangKy->id_hoc_phan_dang_ky = $idHocPhanDangKy;
            $lopHocPhan = LopHocPhan::find($idHocPhanDangKy);
            $idMonHoc = $lopHocPhan->id_mon_hoc;
            $ketQuaDangKy->id_mon_hoc = $idMonHoc;
            $ketQuaDangKy->da_hoc = 2;// lưu bằng 2 để không show ra bảng điểm
            $ketQuaDangKy->diem_giua_ky = null;
            $ketQuaDangKy->diem_cuoi_ky = null;

            //get rate now
            $idTiLeDiem = MonHoc::find($idMonHoc)->id_ti_le_diem;
            $tiLe = TiLeDiem::find($idTiLeDiem);
            $ketQuaDangKy->tl_diem_giua_ky = $tiLe->ti_le_giua_ky;
            $ketQuaDangKy->tl_diem_cuoi_ky = $tiLe->ti_le_cuoi_ky;
            $ketQuaDangKy->id_dot_dang_ky = $idDotDangKy;
            if($ketQuaDangKy->save()) {
                $dangKyMonHoc->sl_hien_tai = $slHienTai + 1;
                if($dangKyMonHoc->save()) {
                    return response()->json([
                        'status'  => true,
                        'message' => trans('Đăng ký thành công'),
                    ]);
                }else {
                    return response()->json([
                        'status'  => false,
                        'message' => trans('Đăng ký không thành công'),
                    ]);
                }
            }
        }
    }
}