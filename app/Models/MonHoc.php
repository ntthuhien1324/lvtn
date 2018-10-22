<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonHoc extends Model
{
    use SoftDeletes;

    protected $table = 'mon_hoc';

    public function tiLeDiem() {
        return $this->belongsTo(TiLeDiem::class,'id_ti_le_diem','id');
    }

    public function hocKy() {
        return $this->belongsToMany(HocKy::class, 'mon_hoc_hoc_ky', 'id_mon_hoc', 'id_hoc_ky');
    }

    public function nhomMonHoc() {
        return $this->belongsToMany(NhomMonHoc::class,'mon_hoc_nhom_mon_hoc','id_mon_hoc','id_nhom_mon_hoc');
    }
}
