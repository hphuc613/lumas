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
    ]
];
