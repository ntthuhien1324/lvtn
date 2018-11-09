<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhongHoc extends Model
{
    use SoftDeletes;

    protected $table = 'phong_hoc';
}
