<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 10:00 CH
 */

namespace App\Admin\Extensions\Grid\Tools;

use App\Admin\Extensions\Grid;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\PerPageSelector as PerPageSelectorBase;

class PerPageSelector extends PerPageSelectorBase
{
    /**
     * Create a new PerPageSelector instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->initialize();
    }

    /**
     * Render PerPageSelector。
     *
     * @return string
     */
    public function render()
    {
        Admin::script($this->script());

        $options = $this->getOptions()->map(function ($option) {
            $selected = ($option == $this->perPage) ? 'selected' : '';
            $url = app('request')->fullUrlWithQuery([$this->perPageName => $option]);

            return "<option value=\"$url\" $selected>$option</option>";
        })->implode("\r\n");

        $show = 'Xem';
        $entries = 'dòng';

        return <<<EOT

<label class="control-label pull-right" style="margin-right: 10px; font-weight: 100;">

        <small>$show</small>&nbsp;
        <select class="input-sm {$this->grid->getPerPageName()}" name="per-page">
            $options
        </select>
        &nbsp;<small>$entries</small>
    </label>

EOT;
    }
}