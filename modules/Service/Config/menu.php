<?php
return [
    'id'         => 'service',
    'name'       => trans('Service'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-cocktail',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'service-type',
            'name'       => trans('Service Type'),
            'route'      => route("get.service_type.list"),
            'middleware' => ''
        ],
        [
            'id'         => 'service',
            'name'       => trans('Service'),
            'route'      => route('get.service.list'),
            'middleware' => ''
        ]
    ]
];
