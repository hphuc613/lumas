<?php
return [
    'id'         => 'member',
    'name'       => trans('Clients'),
    'route'      => route('get.member.list'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-user-friends',
    'middleware' => ['member-view'],
    'group'      => []
];
