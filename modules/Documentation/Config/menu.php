<?php
return [
    'name'       => trans('Documentations'),
    'route'      => route('get.documentation.list'),
    'sort'       => 13,
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
    ]
];
