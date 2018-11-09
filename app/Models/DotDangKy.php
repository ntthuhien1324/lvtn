<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DotDangKy extends Model
{
    use SoftDeletes;

    protected $table = 'dot_dang_ky';
}
