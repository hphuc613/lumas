<?php
return [
    'name'       => trans('Service'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-cocktail',
    'middleware' => [],
    'group'      => [
        [
            'name'       => 'Service Type',
            'route'      => route("get.service_type.list"),
            'middleware' => ''
        ],
        [
            'name'       => 'Service List',
            'route'      => route('get.service.list'),
            'middleware' => ''
        ]
    ]
];
