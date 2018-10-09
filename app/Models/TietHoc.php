<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TietHoc extends Model
{
    use SoftDeletes;

    protected $table = 'tiet_hoc';
}
