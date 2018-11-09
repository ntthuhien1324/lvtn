<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YeuCauMoThemLop extends Model
{
    use SoftDeletes;

    protected $table = 'yeu_cau_mo_them_lop';
}
