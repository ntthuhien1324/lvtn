<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 12/11/2018
 * Time: 1:35 SA
 */

namespace App\Admin\Extensions;

use App\Admin\Extensions\Form\Builder;
use Encore\Admin\Form as FormBase;
use Closure;

class Form extends FormBase
{
    /**
     * Create a new form instance.
     *
     * @param $model
     * @param \Closure $callback
     */
    public function __construct($model, Closure $callback = null)
    {
        $this->model = $model;

        $this->builder = new Builder($this);

        if ($callback instanceof Closure) {
            $callback($this);
        }
    }
}