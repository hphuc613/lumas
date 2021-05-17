<?php
return [
    'name'       => trans('Settings'),
    'route'      => '#',
    'sort'       => 99,
    'active'     => true,
    'icon'       => 'fas fa-cog',
    'middleware' => ['settings'],
    'group'      => [
        [
            'name'       => trans('Settings'),
            'route'      => route('get.setting.list'),
            'middleware' => ['setting-basic'],
        ],
        [
            'name'       => trans('File Manager'),
            'route'      => route('elfinder.index'),
            'middleware' => ['setting-file-manager'],
        ]
    ]
];
