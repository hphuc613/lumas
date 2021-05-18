<?php
return [
    'id'         => 'member',
    'name'       => trans('Client'),
    'route'      => '#',
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-user-friends',
    'middleware' => ['member'],
    'group'      => [
        [
            'id'         => 'member-type',
            'name'       => trans('Client Type'),
            'route'      => route("get.member_type.list"),
            'middleware' => ''
        ],
        [
            'id'         => 'member',
            'name'       => trans('Client'),
            'route'      => route('get.member.list'),
            'middleware' => ['member']
        ],
    ]
];
