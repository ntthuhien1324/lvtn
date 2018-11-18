<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HocKy extends Model
{
    use SoftDeletes;

    protected $table = 'hoc_ky';

    public function namHoc() {
        return $this->belongsTo(NamHoc::class, 'id_nam_hoc', 'id');
    }

    public function mon_hoc() {
        return $this->belongsToMany(MonHoc::class, 'mon_hoc_hoc_ky', 'id_hoc_ky', 'id_mon_hoc');
    }
}
