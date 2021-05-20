<?php
return [
    'id'         => 'store',
    'name'       => trans('Stores'),
    'route'      => route('get.store.list'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-store',
    'middleware' => ['store'],
    'group'      => []
];
