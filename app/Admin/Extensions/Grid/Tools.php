<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 9:15 CH
 */

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Grid\Tools as ToolsBase;
use App\Admin\Extensions\Grid\Tools\BatchActions;
use App\Admin\Extensions\Grid\Tools\RefreshButton;
use App\Admin\Extensions\Grid\Tools\FilterButton;

class Tools extends ToolsBase
{
    protected function appendDefaultTools()
    {
        $this->append(new BatchActions())
            ->append(new RefreshButton())
            ->append(new FilterButton());
    }
}