<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 08/10/2018
 * Time: 9:59 CH
 */

namespace App\Admin\Extensions\Grid\Tools;

use Encore\Admin\Grid\Tools\Paginator as PaginatorBase;

class Paginator extends PaginatorBase
{
    /**
     * Get per-page selector.
     *
     * @return PerPageSelector
     */
    protected function perPageSelector()
    {
        return new PerPageSelector($this->grid);
    }

    /**
     * Get Pagination links.
     *
     * @return string
     */
    protected function paginationLinks()
    {
        return $this->paginator->render('admin::pagination');
    }
}