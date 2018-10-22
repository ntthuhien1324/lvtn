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
}
