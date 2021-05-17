<?php

return [
    'id'         => 'access-control',
    'name'       => trans('Access Control'),
    'route'      => route('get.access_control.index'),
    'sort'       => 99,
    'active'     => true,
    'icon'       => 'fab fa-delicious',
    'middleware' => ['permission-view'],
    'group'      => []
];
