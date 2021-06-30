<?php

return [
    'id'         => 'user',
    'name'       => trans('Users'),
    'route'      => route('get.user.list'),
    'sort'       => 9,
    'active'     => true,
    'icon'       => 'fas fa-user',
    'middleware' => ['users'],
    'group'      => []
];
