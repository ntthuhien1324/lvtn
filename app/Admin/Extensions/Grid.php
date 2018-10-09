<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 07/10/2018
 * Time: 3:05 CH
 */

namespace App\Admin\Extensions;

use Encore\Admin\Grid as GridBase;
use Encore\Admin\Grid\Displayers\Actions;

class Grid extends GridBase
{
    /**
     * Add `actions` column for grid.
     *
     * @return void
     */
    protected function appendActionsColumn()
    {
        if (!$this->option('useActions')) {
            return;
        }

        $grid = $this;
        $callback = $this->actionsCallback;
        $column = $this->addColumn('__actions__', 'Hành động');

        $column->display(function ($value) use ($grid, $column, $callback) {
            $actions = new Actions($value, $grid, $column, $this);

            return $actions->display($callback);
        });
    }

    /**
     * Render create button for grid.
     *
     * @return string
     */
    public function renderCreateButton()
    {
        return (new Grid\Tools\CreateButton($this))->render();
    }

    /**
     * View for grid to render.
     *
     * @var string
     */
    protected $view = 'encore_custom.grid.table';

    /**
     * Setup grid tools.
     */
    public function setupTools()
    {
        $this->tools = new Grid\Tools($this);
    }

    /**
     * Setup grid filter.
     *
     * @return void
     */
    protected function setupFilter()
    {
        $this->filter = new Grid\Filter($this->model());
    }

    /**
     * Render export button.
     *
     * @return string
     */
    public function renderExportButton()
    {
        return (new Grid\Tools\ExportButton($this))->render();
    }

    /**
     * Get the grid paginator.
     *
     * @return mixed
     */
    public function paginator()
    {
        return new Grid\Tools\Paginator($this);
    }

}