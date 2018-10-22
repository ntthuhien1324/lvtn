<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiLeDiem extends Model
{
    use SoftDeletes;

    protected $table = 'ti_le_diem';
}
