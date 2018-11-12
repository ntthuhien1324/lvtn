<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 12/11/2018
 * Time: 9:22 CH
 */

namespace App\Admin\Extensions\Form\Field;

use Encore\Admin\Form\Field\HasMany as HasManyBase;

class HasMany extends HasManyBase
{
    protected $views = [
        'default' => 'encore_custom.form.hasmany',
        'tab'     => 'encore_custom.form.hasmanytab',
    ];
}