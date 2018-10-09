<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 10:34 CH
 */

namespace App\Admin\Extensions\Show;

use App\Admin\Extensions\Show;
use Encore\Admin\Show\Panel as PanelBase;
use Illuminate\Support\Collection;

class Panel extends PanelBase
{
    /**
     * Panel constructor.
     */
    public function __construct(Show $show)
    {
        $this->parent = $show;

        $this->initData();
    }

    /**
     * Initialize view data.
     */
    protected function initData()
    {
        $this->data = [
            'fields' => new Collection(),
            'tools'  => new Show\Tools($this),
            'style'  => 'info',
            'title'  => 'Chi tiết',
        ];
    }
}