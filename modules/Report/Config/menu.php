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
            'id'         => 'report-treatment',
            'name'       => trans('Treatment Information'),
            'route'      => route('get.report.treatment'),
            'middleware' => []
        ],
    ]
];
