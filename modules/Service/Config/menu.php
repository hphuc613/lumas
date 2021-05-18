<?php
return [
    'id'         => 'service',
    'name'       => trans('Service'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-cocktail',
    'middleware' => 'service',
    'group'      => [
        [
            'id'         => 'service-type',
            'name'       => trans('Service Type'),
            'route'      => route("get.service_type.list"),
            'middleware' => ['service-type']
        ],
        [
            'id'         => 'service',
            'name'       => trans('Service'),
            'route'      => route('get.service.list'),
            'middleware' => 'service'
        ]
    ]
];
