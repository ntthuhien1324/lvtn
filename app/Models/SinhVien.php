<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SinhVien extends Model
{
    use SoftDeletes;

    protected $table = 'sinh_vien';

    public function setPasswordAttribute($password)
    {
        if($password && $password != $this->password) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
