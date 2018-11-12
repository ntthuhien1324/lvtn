<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LopHocPhan extends Model
{
    use SoftDeletes;

    protected $table = 'lop_hoc_phan';

    public function thoiGianHoc(){
        return $this->hasMany(ThoiGianHoc::class, 'id_hoc_phan_dang_ky','id');
    }
}
