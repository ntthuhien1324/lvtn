<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 12/11/2018
 * Time: 1:39 SA
 */

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Builder as BuilderBase;
use Encore\Admin\Form\Field;

class Builder extends BuilderBase
{
    /**
     * Remove reserved fields like `id` `created_at` `updated_at` in form fields.
     *
     * @return void
     */
    protected function removeReservedFields()
    {
        if (!$this->isMode(static::MODE_CREATE)) {
            return;
        }
        $reservedColumns = [
//            $this->form->model()->getKeyName(),
            $this->form->model()->getCreatedAtColumn(),
            $this->form->model()->getUpdatedAtColumn(),
        ];

        $this->fields = $this->fields()->reject(function (Field $field) use ($reservedColumns) {
            return in_array($field->column(), $reservedColumns);
        });
    }
}