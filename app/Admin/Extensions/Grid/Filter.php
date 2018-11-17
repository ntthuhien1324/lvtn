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

    /**
     * Remove ID filter if needed.
     */
    public function removeIDFilterIfNeeded()
    {
        if (!$this->useIdFilter && !$this->idFilterRemoved) {
            $this->removeDefaultIDFilter();

            $this->layout->removeDefaultIDFilter();

            $this->idFilterRemoved = true;
        }
    }

    /**
     * Remove the default ID filter.
     */
    protected function removeDefaultIDFilter()
    {
        array_shift($this->filters);
    }

    /**
     * Initialize filter layout.
     */
    protected function initLayout()
    {
        $this->layout = new Filter\Layout\Layout($this);
    }
}