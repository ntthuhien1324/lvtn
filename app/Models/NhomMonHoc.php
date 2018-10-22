<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhomMonHoc extends Model
{
    use SoftDeletes;

    protected $table = 'nhom_mon_hoc';

    public function monHoc() {
        return $this->belongsToMany(MonHoc::class,'mon_hoc_nhom_mon_hoc','id_nhom_mon_hoc','id_mon_hoc');
    }
}
