<?php
return [
    'id'         => 'service',
    'name'       => trans('Service Management'),
    'sort'       => 1,
    'active'     => true,
    'icon'       => 'fas fa-cocktail',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'service-type',
            'name'       => trans('Service Types'),
            'route'      => route("get.service_type.list"),
            'middleware' => ['service-type']
        ],
        [
            'id'         => 'service',
            'name'       => trans('Services'),
            'route'      => route('get.service.list'),
            'middleware' => 'service'
        ],
        [
            'id'         => 'voucher',
            'name'       => trans('Voucher'),
            'route'      => route('get.voucher.list'),
            'middleware' => 'voucher'
        ]
    ]
];
