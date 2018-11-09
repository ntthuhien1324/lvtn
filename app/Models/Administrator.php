<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/11/2018
 * Time: 1:07 CH
 */

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator as AdministratorBase;

class Administrator extends AdministratorBase
{
    protected $fillable = ['email', 'id_lop', 'kieu_nguoi_dung', 'username', 'password', 'name', 'avatar'];
}