<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrangThaiSinhVien extends Model
{
    use SoftDeletes;

    protected $table = 'trang_thai_sinh_vien';
}
