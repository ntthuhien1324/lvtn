<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThoiGianHoc extends Model
{
    use SoftDeletes;

    protected $table = 'thoi_gian_hoc';
    protected $fillable = ['ngay','id_phong_hoc' ,'gio_bat_dau', 'gio_ket_thuc'];
}
