<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 18/11/2018
 * Time: 12:50 SA
 */

namespace App\Admin\Extensions\Grid\Filter\Layout;

use Encore\Admin\Grid\Filter\Layout\Layout as LayoutBase;

class Layout extends LayoutBase
{
    /**
     * Remove the default ID filter of the default(first) column.
     */
    public function removeDefaultIDFilter()
    {
        $this->columns()
            ->first()
            ->filters()
            ->shift()
        ;
    }
}