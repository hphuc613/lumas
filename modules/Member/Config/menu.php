<?php
return [
    'name' => trans('Member'),
    'route' => route('get.member.list'),
    'sort' => 1,
    'active'=> TRUE,
    'icon' => 'fas fa-user-friends',
    'middleware' => ['member-view'],
    'group' => []
];
