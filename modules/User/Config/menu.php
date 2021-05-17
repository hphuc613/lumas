<?php

return [
    'id'         => 'user',
    'name'       => trans('Users'),
    'route'      => route('get.user.list'),
    'sort'       => 97,
    'active'     => true,
    'icon'       => 'fas fa-user',
    'middleware' => ['users'],
    'group'      => []
];
