<?php
return [
    'name'       => trans('Report'),
    'route'      => "#",
    'sort'       => 9,
    'active'     => TRUE,
    'id'         => 'report',
    'icon'       => 'fas fa-paste',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'report-service',
            'name'       => trans('Service Information'),
            'route'      => route('get.report.service'),
            'middleware' => []
        ],
        [
            'id'         => 'report-sale',
            'name'       => trans('Sales Report'),
            'route'      => route('get.report.sale'),
            'middleware' => []
        ],
        [
            'id'         => 'report-service-expendable',
            'name'       => trans('Service Expendable Report'),
            'route'      => route('get.report.service_expendable'),
            'middleware' => []
        ],
        [
            'id'         => 'report-service-provide',
            'name'       => trans('Service Provide Report'),
            'route'      => route('get.report.service_provide'),
            'middleware' => []
        ],
    ]
];
