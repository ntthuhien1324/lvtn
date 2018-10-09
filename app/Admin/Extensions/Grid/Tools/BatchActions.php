<?php
namespace App\Admin\Extensions\Grid\Tools;

use Encore\Admin\Grid\Tools\BatchActions as BatchActionsBase;
use Encore\Admin\Grid\Tools\BatchDelete;

class BatchActions extends BatchActionsBase
{
    /**
     * Append default action(batch delete action).
     *
     * return void
     */
    protected function appendDefaultAction()
    {
        $this->add(new BatchDelete('Xóa'));
    }

    /**
     * Render BatchActions button groups.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->enableDelete) {
            $this->actions->shift();
        }

        if ($this->actions->isEmpty()) {
            return '';
        }

        $this->setUpScripts();

        $data = [
            'actions'       => $this->actions,
            'selectAllName' => $this->grid->getSelectAllName(),
        ];

        return view('encore_custom.grid.batch-actions', $data)->render();
    }
}