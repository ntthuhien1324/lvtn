<?php
namespace App\Admin\Extensions\Grid\Tools;

use App\Admin\Extensions\Grid;
use Encore\Admin\Grid\Tools\ExportButton as ExportButtonBase;

class ExportButton extends ExportButtonBase
{
    /**
     * Create a new Export button instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->allowExport()) {
            return '';
        }

        $this->setUpScripts();

        $export = 'Xuất dữ liệu';
        $all = 'Tất cả';
        $currentPage = 'Trang hiện tại';
        $selectedRows = 'Dòng được chọn';

        $page = request('page', 1);

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a class="btn btn-sm btn-twitter" title="{$export}"><i class="fa fa-download"></i><span class="hidden-xs"> {$export}</span></a>
    <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{$this->grid->getExportUrl('all')}" target="_blank">{$all}</a></li>
        <li><a href="{$this->grid->getExportUrl('page', $page)}" target="_blank">{$currentPage}</a></li>
        <li><a href="{$this->grid->getExportUrl('selected', '__rows__')}" target="_blank" class='{$this->grid->getExportSelectedName()}'>{$selectedRows}</a></li>
    </ul>
</div>
EOT;
    }
}