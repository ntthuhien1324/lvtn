<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LopHocPhan extends Model
{
    use SoftDeletes;

    protected $table = 'lop_hoc_phan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType  = 'string';
    protected $fillable = ['sl_hien_tai','id'];

    public function thoi_gian_hoc(){
        return $this->hasMany(ThoiGianHoc::class, 'id_hoc_phan_dang_ky','id');
    }
}
