<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 18/11/2018
 * Time: 12:12 SA
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyMonHoc extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'mon_hoc_dang_ky';
    protected $fillable = ['sl_hien_tai','id'];
    public $incrementing = false;
}