<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GopY extends Model
{
    use SoftDeletes;

    protected $table = 'gop_y';
}
