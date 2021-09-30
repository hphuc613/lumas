<?php
return [
    'name'       => trans('Room'),
    'route'      => route('get.room.list'),
    'sort'       => 8,
    'active'     => TRUE,
    'id'         => 'room',
    'icon'       => 'fas fa-door-open',
    'middleware' => ['room'],
    'group'      => []
];
