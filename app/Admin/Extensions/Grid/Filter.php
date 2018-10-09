<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 9:44 CH
 */

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Grid\Filter as FilterBase;

class Filter extends FilterBase
{
    /**
     * @var string
     */
    protected $view = 'encore_custom.filter.container';
}