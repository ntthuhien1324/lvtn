<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 9:22 CH
 */

namespace App\Admin\Extensions\Grid\Tools;

use Encore\Admin\Grid\Tools\FilterButton as FilterButtonBase;

class FilterButton extends FilterButtonBase
{
    /**
     * @var string
     */
    protected $view = 'encore_custom.filter.button';
}