<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 17/11/2018
 * Time: 2:06 CH
 */

namespace App\Http\Extensions;

use Encore\Admin\Layout\Content;

class ContentSinhVien extends Content
{
    public function render()
    {
        $items = [
            'header'      => $this->header,
            'description' => $this->description,
            'breadcrumb'  => $this->breadcrumb,
            'content'     => $this->build(),
        ];

        return view('user.content', $items)->render();
    }
}