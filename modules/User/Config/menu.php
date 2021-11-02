<?php

return [
    'id'         => 'user',
    'name'       => trans('User'),
    'route'      => '#',
    'sort'       => 9,
    'active'     => true,
    'icon'       => 'fas fa-user',
    'middleware' => ['users'],
    'group'      => [
        [
            'id'         => 'user',
            'name'       => trans('Users'),
            'route'      => route('get.user.list'),
            'middleware' => []
        ],
        [
            'id'         => 'salary-list',
            'name'       => trans('Salary'),
            'route'      => route('get.user.salary_list'),
            'middleware' => []
        ],
    ]
];
