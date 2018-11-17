<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 17/11/2018
 * Time: 2:21 CH
 */

namespace App\Http\Extensions;

use App\Admin\Extensions\Grid as GridAdmin;

class Grid extends GridAdmin
{
    protected $view = 'user.grid.table';
}