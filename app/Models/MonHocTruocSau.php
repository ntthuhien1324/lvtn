<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonHocTruocSau extends Model
{
    use SoftDeletes;

    protected $table = 'mon_hoc_truoc_sau';
}
