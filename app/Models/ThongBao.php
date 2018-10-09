<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThongBao extends Model
{
    use SoftDeletes;

    protected $table = 'thong_bao';
}
