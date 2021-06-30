<?php

return [
    'id'         => 'role',
    'name'       => trans('Roles'),
    'route'      => route('get.role.list'),
    'sort'       => 10,
    'active'     => true,
    'icon'       => 'fas fa-user-tag',
    'middleware' => ['roles'],
    'group'      => []
];
