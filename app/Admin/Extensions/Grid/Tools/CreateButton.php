<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 07/10/2018
 * Time: 3:45 CH
 */

namespace App\Admin\Extensions\Grid\Tools;

use Encore\Admin\Grid\Tools\CreateButton as CreateButtonBase;

class CreateButton extends CreateButtonBase
{
    public function render()
    {
        if (!$this->grid->allowCreation()) {
            return '';
        }

        $new = 'Thêm';

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{$this->grid->getCreateUrl()}" class="btn btn-sm btn-success" title="{$new}">
        <i class="fa fa-file-o"></i><span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
    </a>
</div>

EOT;
    }
}