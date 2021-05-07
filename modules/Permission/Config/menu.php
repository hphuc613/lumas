<?php

use Modules\Base\Model\Status;

return [
    'name'       => trans('Access Control'),
    'route'      => route('get.access_control.index'),
    'sort'       => 99,
    'active'     => TRUE,
    'icon'       => 'fab fa-delicious',
    'middleware' => ['permission-view'],
    'group'      => []
];
