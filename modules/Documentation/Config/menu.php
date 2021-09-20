<?php
return [
    'name'       => trans('Documentations'),
    'route'      => route('get.documentation.list'),
    'sort'       => 11,
    'active'     => TRUE,
    'id'         => 'documentations',
    'icon'       => 'fas fa-book',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'documentation',
            'name'       => trans('Website'),
            'route'      => route('get.documentation.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'documentation-mobile',
            'name'       => trans('Mobile'),
            'route'      => route('get.documentation_mobile.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'guide-client',
            'name'       => trans('Client Guide'),
            'route'      => route('get.guide_client.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'guide-staff',
            'name'       => trans('Staff Guide'),
            'route'      => route('get.guide_staff.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'documentation-ct',
            'name'       => trans('用戶指南網站'),
            'route'      => route('get.documentation_ct.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'documentation-mobile-ct',
            'name'       => trans('移動應用程序 - 用戶指南'),
            'route'      => route('get.documentation_mobile_ct.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'guide-client-tc',
            'name'       => trans('客戶指南'),
            'route'      => route('get.guide_client_tc.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'guide-staff-tc',
            'name'       => trans('員工指南'),
            'route'      => route('get.guide_staff_tc.list'),
            'middleware' => ['course-category'],
        ],
    ]
];
