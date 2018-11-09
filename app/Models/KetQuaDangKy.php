<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KetQuaDangKy extends Model
{
    use SoftDeletes;

    protected $table = 'ket_qua_dang_ky';
}
