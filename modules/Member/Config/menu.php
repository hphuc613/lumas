<?php
return [
    'id'         => 'member',
    'name'       => trans('Client Management'),
    'route'      => '#',
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-user-friends',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'member-type',
            'name'       => trans('Client Types'),
            'route'      => route("get.member_type.list"),
            'middleware' => 'member-type'
        ],
        [
            'id'         => 'member',
            'name'       => trans('Clients'),
            'route'      => route('get.member.list'),
            'middleware' => ['member']
        ],
    ]
];
