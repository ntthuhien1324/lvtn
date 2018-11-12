<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 13/11/2018
 * Time: 2:49 SA
 */

namespace App\Http\Controllers;

use App\Models\ThongBao;

class ThongBaoLoginController extends Controller
{
    public function list() {
        $thongBao = ThongBao::paginate(4);
        return view('user.getLogin',['thongBao'=>$thongBao]);
    }
}
