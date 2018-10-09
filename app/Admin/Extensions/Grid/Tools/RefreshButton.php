<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 9:17 CH
 */

namespace App\Admin\Extensions\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class RefreshButton extends AbstractTool
{
    /**
     * Script for this tool.
     *
     * @return string
     */
    protected function script()
    {
        $message = 'Tải lại thành công';

        return <<<EOT

$('.grid-refresh').on('click', function() {
    $.pjax.reload('#pjax-container');
    toastr.success('{$message}');
});

EOT;
    }

    /**
     * Render refresh button of grid.
     *
     * @return string
     */
    public function render()
    {
        Admin::script($this->script());

        $refresh = trans('Tải lại');

        return <<<EOT
<a class="btn btn-sm btn-primary grid-refresh" title="$refresh"><i class="fa fa-refresh"></i><span class="hidden-xs"> $refresh</span></a>
EOT;
    }
}