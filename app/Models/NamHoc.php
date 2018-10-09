<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NamHoc extends Model
{
    use SoftDeletes;

    protected $table = 'nam_hoc';

    public function hocKy() {
        return $this->hasMany(HocKy::class, 'id_nam_hoc', 'id');
    }
}
