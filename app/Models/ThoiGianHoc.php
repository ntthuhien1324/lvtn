<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThoiGianHoc extends Model
{
    use SoftDeletes;

    protected $table = 'thoi_gian_hoc';
}
