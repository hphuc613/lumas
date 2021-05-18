<?php
return [
    'id'         => 'store',
    'name'       => trans('Store'),
    'route'      => route('get.store.list'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-store',
    'middleware' => ['store'],
    'group'      => []
];
