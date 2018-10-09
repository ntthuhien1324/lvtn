<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 10:29 CH
 */

namespace App\Admin\Extensions;

use App\Admin\Extensions\Show\Panel;
use Encore\Admin\Show as ShowBase;

class Show extends ShowBase
{
    /**
     * Initialize panel.
     */
    protected function initPanel()
    {
        $this->panel = new Panel($this);
    }
}